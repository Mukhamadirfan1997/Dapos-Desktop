import { app, BrowserWindow, ipcMain, shell } from 'electron';
import { spawn } from 'child_process';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const projectRoot = path.resolve(__dirname, '..');

const GITHUB_REPO = 'Mukhamadirfan1997/Dapos-Desktop';

let phpServer = null;
let mainWindow = null;

import net from 'net';

function getFreePort() {
    return new Promise((resolve) => {
        const srv = net.createServer();
        srv.listen(0, () => {
            const port = srv.address().port;
            srv.close(() => resolve(port));
        });
    });
}

function getDatabasePath() {
    return path.join(app.getPath('userData'), 'database.sqlite');
}

function ensureDatabase() {
    const target = getDatabasePath();
    fs.mkdirSync(path.dirname(target), { recursive: true });
    if (!fs.existsSync(target)) {
        const source = path.join(projectRoot, 'database', 'seed.sqlite');
        if (fs.existsSync(source)) {
            fs.copyFileSync(source, target);
            console.log(`Database dibuat di: ${target}`);
        }
    }
    return target;
}

function runPhpCommand(args, env) {
    return new Promise((resolve, reject) => {
        const child = spawn('php', args, {
            cwd: projectRoot,
            env,
            stdio: ['ignore', 'pipe', 'pipe'],
        });
        child.stdout.on('data', (data) => console.log(`[PHP] ${data.toString()}`));
        child.stderr.on('data', (data) => console.error(`[PHP] ${data.toString()}`));
        child.on('close', (code) => {
            if (code === 0) resolve();
            else reject(new Error(`php ${args.join(' ')} gagal (kode ${code})`));
        });
    });
}

async function startPhpServer(port, databasePath) {
    const publicDir = path.join(projectRoot, 'public');
    const storageDir = path.join(projectRoot, 'storage');

    if (!fs.existsSync(storageDir)) {
        fs.mkdirSync(storageDir, { recursive: true });
    }

    const env = Object.assign({}, process.env, {
        APP_ENV: 'production',
        APP_DEBUG: 'false',
        APP_URL: `http://localhost:${port}`,
        DB_DATABASE: databasePath,
    });

    phpServer = spawn('php', [
        'artisan', 'serve',
        '--port', port.toString(),
        '--host', '127.0.0.1',
        '--env', '.env',
    ], {
        cwd: projectRoot,
        env,
        stdio: ['ignore', 'pipe', 'pipe'],
    });

    phpServer.stdout.on('data', (data) => {
        console.log(`[PHP] ${data.toString()}`);
    });

    phpServer.stderr.on('data', (data) => {
        console.error(`[PHP] ${data.toString()}`);
    });

    phpServer.on('close', (code) => {
        console.log(`[PHP] Server exited with code ${code}`);
    });

    return new Promise((resolve) => {
        setTimeout(() => resolve(), 2000);
    });
}

async function createWindow(port) {
    mainWindow = new BrowserWindow({
        width: 1280,
        height: 800,
        minWidth: 1024,
        minHeight: 600,
        icon: path.join(__dirname, 'icon.png'),
        webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
            nodeIntegration: false,
            contextIsolation: true,
        },
        autoHideMenuBar: true,
        title: 'DAPOS Desktop',
        show: false,
    });

    mainWindow.loadURL(`http://127.0.0.1:${port}/dapos`);

    mainWindow.once('ready-to-show', () => {
        mainWindow.show();
    });

    mainWindow.webContents.once('did-finish-load', () => {
        silentCheckUpdate(mainWindow);
    });

    mainWindow.on('closed', () => {
        mainWindow = null;
    });
}

function compareVersions(a, b) {
    const pa = String(a || '0').split('.').map(Number);
    const pb = String(b || '0').split('.').map(Number);
    const len = Math.max(pa.length, pb.length);
    for (let i = 0; i < len; i++) {
        const va = pa[i] || 0;
        const vb = pb[i] || 0;
        if (va > vb) return 1;
        if (va < vb) return -1;
    }
    return 0;
}

async function checkUpdate() {
    try {
        const res = await fetch(`https://api.github.com/repos/${GITHUB_REPO}/releases/latest`, {
            headers: { 'User-Agent': 'DAPOS-Desktop', 'Accept': 'application/vnd.github+json' },
        });
        if (res.status === 404) {
            return { status: 'latest', tag: null, version: null, url: null, notes: null };
        }
        if (!res.ok) {
            return { status: 'error', message: 'HTTP ' + res.status };
        }
        const data = await res.json();
        const tag = data.tag_name || '';
        const version = tag.replace(/^v/i, '');
        const current = app.getVersion();
        return {
            status: compareVersions(version, current) > 0 ? 'update' : 'latest',
            tag,
            version,
            url: data.html_url || `https://github.com/${GITHUB_REPO}/releases/latest`,
            notes: data.body || null,
        };
    } catch (e) {
        return { status: 'error', message: e.message || 'Koneksi gagal' };
    }
}

async function silentCheckUpdate(win) {
    const result = await checkUpdate();
    if (result.status === 'update' && win && !win.isDestroyed()) {
        win.webContents.send('update:available', result);
    }
}

app.whenReady().then(async () => {
    ipcMain.handle('check-update', () => checkUpdate());
    ipcMain.handle('open-external', (_event, url) => {
        if (typeof url === 'string' && url) shell.openExternal(url);
    });
    ipcMain.handle('app-version', () => app.getVersion());

    const port = await getFreePort();
    const databasePath = ensureDatabase();
    await runPhpCommand(['artisan', 'migrate', '--force'], Object.assign({}, process.env, {
        APP_ENV: 'production',
        APP_DEBUG: 'false',
        DB_DATABASE: databasePath,
    }));
    console.log(`Database siap: ${databasePath}`);
    console.log(`Starting PHP server on port ${port}...`);
    await startPhpServer(port, databasePath);
    console.log(`PHP server started on http://127.0.0.1:${port}`);
    await createWindow(port);
});

app.on('window-all-closed', () => {
    if (phpServer) {
        phpServer.kill();
        phpServer = null;
    }
    if (process.platform !== 'darwin') {
        app.quit();
    }
});

app.on('activate', () => {
    if (mainWindow === null) {
        const port = parseInt(process.env.PORT || '8000');
        createWindow(port);
    }
});

app.on('before-quit', () => {
    if (phpServer) {
        phpServer.kill();
        phpServer = null;
    }
});
