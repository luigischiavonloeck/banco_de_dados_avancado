const { MongoClient } = require("mongodb");
const mongo = new MongoClient("mongodb://192.168.0.116:27017");
async function run() {
    try {
        const database = mongo.db("empregos");
        const documents = database.collection("empregosti");
        const query = { empregos: { $gt: 500 }, ano:2021 };
        const options = {
            sort: { empregos: 1 },
            projection: { _id: 0, empregos: 1, subsetor: 1, regiao: 1 },
        };

        const result = await documents.find(query,options)

        for await (const doc of result)
            console.dir(doc);
    }finally {
        await mongo.close();
    }
}
run().catch(console.dir);