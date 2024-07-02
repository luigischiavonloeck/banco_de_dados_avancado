import db from "../libs/firebase/rtdb_connection.js"
import { onValue, ref } from "firebase/database";

const node = "user"
const userRef = ref(db,node)

onValue(userRef,(snapshot)=>{
    console.table(snapshot.val())
    // process.exit(0);
});