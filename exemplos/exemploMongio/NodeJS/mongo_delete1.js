const { MongoClient } = require("mongodb");
const mongo = new MongoClient("mongodb://192.168.0.116:27017");
async function run() {
    try {
        const database = mongo.db("empregos");
        const col_pessoas = database.collection("pessoas");
        const query = { username: "adminJS"};
        const result = await col_pessoas.deleteMany(query);

        console.log(`${result.deletedCount} documento(s) removidos(s)`);
    }finally {
        await mongo.close();
    }
}
run().catch(console.dir);