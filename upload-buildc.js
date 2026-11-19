//npm install basic-ftp 212.85.6.191
const ftp = require("basic-ftp");
const path = require("path");

async function main() {
    const client = new ftp.Client();
    client.ftp.verbose = true;

    try {
        await client.access({
            host: "212.85.6.191",
            user: "u503068802",
            password: "Pk2k3@noslimde", // 🔒 Substitua por sua senha FTP
            secure: false
        });

        // Caminho local dos arquivos já compilados
        const localBuildPath = path.join(__dirname, "public/build");

        // Pasta remota onde os arquivos irão (em public_html/public/build)
        const remoteBuildPath = "/public_html/public/build";

        await client.ensureDir(remoteBuildPath);     // Garante que a pasta existe
        await client.clearWorkingDir();              // Limpa a pasta remota antes do upload (opcional)
        await client.uploadFromDir(localBuildPath);  // Envia os arquivos

        console.log("✅ Upload concluído com sucesso!");
    } catch (err) {
        console.error("❌ Erro no upload:", err);
    }

    client.close();
}

main();
