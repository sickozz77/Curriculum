//npm install basic-ftp 212.85.6.191
// upload-build.js
import ftp from "basic-ftp";
import { join } from "path";
import { fileURLToPath } from "url";
import { dirname } from "path";

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

async function main() {
    const client = new ftp.Client();
    client.ftp.verbose = true;

    try {
        await client.access({
            host: "212.85.6.191",
            user: "u503068802",
            password: "Pk2k3@noslimde",
            secure: false
        });

        const localBuildPath = join(__dirname, "public/build");
        const remoteBuildPath = "/public_html/public/build";

        await client.ensureDir(remoteBuildPath);
        await client.clearWorkingDir();
        await client.uploadFromDir(localBuildPath);

        console.log("✅ Upload concluído com sucesso!");
    } catch (err) {
        console.error("❌ Erro no upload:", err);
    }

    client.close();
}

main();
