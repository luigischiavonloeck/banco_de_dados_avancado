import { onValue, ref } from "firebase/database";
import db from "../libs/firebase/rtdb_connection.js"

const node = "users"
//ONVALUE ONLY-ONCE
onValue(ref(db,node),(snapshot)=>{ 
	console.table(snapshot.val())
},{onlyOnce:true});