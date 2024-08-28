const { MongoClient } = require("mongodb");
const mongo = new MongoClient("mongodb://192.168.0.116:27017");
async function run() {
    try {
        const database = mongo.db("empregos");
        const col_pessoas = database.collection("pessoas");

        const filter = { idade: 10 };
        const updateDoc = {
            $set: {idade: 11},
        };

        const result = await col_pessoas.updateOne(filter, updateDoc);
        console.log(`${result.modifiedCount} documento(s) Atualizados(s) `);
    }finally {
        await mongo.close();
    }
}
run().catch(console.dir);