const { MongoClient } = require("mongodb");
const manager = new MongoClient("mongodb://192.168.0.116:27017");
async function run() {
    try {
        const database = manager.db("empregos");
        const col_pessoas = database.collection("pessoas");
        const document1 = {
            username: 'adminJS',
            email: 'adminJS@example.com',
            nome: 'AdminJS User',
        }

        const result = await col_pessoas.insertOne(document1);
        console.log(`${result.insertedId} Documento(s) Inserido(s) `);
    }finally {
        await manager.close();
    }
}
run().catch(console.dir);