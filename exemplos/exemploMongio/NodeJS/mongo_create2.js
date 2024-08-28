const { MongoClient } = require("mongodb");
const manager = new MongoClient("mongodb://192.168.0.116:27017");
async function run() {
    try {
        const database = manager.db("empregos");
        const col_pessoas = database.collection("pessoas");
        const docs = [{
            username: 'adminJS',
            email: 'adminJS@example.com',
            nome: 'AdminJS User',
        },{
            username: 'user1JS',
            email: 'userJS@example.com',
            nome: 'Basic UserJS',
        }]

        const result = await col_pessoas.insertMany(docs,{ ordered: true });
        console.log(`${result.insertedCount} documento(s) inserido(s)`);
    }finally {
        await manager.close();
    }
}
run().catch(console.dir);