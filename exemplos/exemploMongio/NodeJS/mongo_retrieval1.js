const { MongoClient } = require("mongodb");
const mongo = new MongoClient("mongodb://192.168.0.116:27017");
async function run() {
    try {
        const database = mongo.db("empregos");
        const documents = database.collection("municipios");
        const result = await documents.find()

        for await (const doc of result)
            console.dir(doc);
    }finally {
        await mongo.close();
    }
}
run().catch(console.dir);