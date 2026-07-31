import { app, BrowserWindow } from 'electron';
import { spawn } from 'child_process';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const projectRoot = path.resolve(__dirname, '..');

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

async function startPhpServer(port) {
    const publicDir = path.join(projectRoot, 'public');
    const storageDir = path.join(projectRoot, 'storage');

    if (!fs.existsSync(storageDir)) {
        fs.mkdirSync(storageDir, { recursive: true });
    }

    const env = Object.assign({}, process.env, {
        APP_ENV: 'production',
        APP_DEBUG: 'false',
        APP_URL: `http://localhost:${port}`,
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
            nodeIntegration: false,
            contextIsolation: true,
        },
        autoHideMenuBar: true,
        title: 'DAPOS v8.7 Desktop',
        show: false,
    });

    mainWindow.loadURL(`http://127.0.0.1:${port}/dapos`);

    mainWindow.once('ready-to-show', () => {
        mainWindow.show();
    });

    mainWindow.on('closed', () => {
        mainWindow = null;
    });
}

app.whenReady().then(async () => {
    const port = await getFreePort();
    console.log(`Starting PHP server on port ${port}...`);
    await startPhpServer(port);
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
