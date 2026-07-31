import { contextBridge, ipcRenderer } from 'electron';

contextBridge.exposeInMainWorld('daposApi', {
    checkUpdate: () => ipcRenderer.invoke('check-update'),
    openExternal: (url) => ipcRenderer.invoke('open-external', url),
    getVersion: () => ipcRenderer.invoke('app-version'),
    onUpdateAvailable: (callback) => {
        ipcRenderer.on('update:available', (_event, data) => callback(data));
    },
});
