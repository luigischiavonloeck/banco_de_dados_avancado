import db from "../libs/firebase/rtdb_connection.js"
import { onChildChanged, ref } from "firebase/database";

const node = "user"
//CHILD ADDED
let count =0;
let refDB = ref(db,node);

onChildChanged(refDB,(changedSnapshot)=>{ //()=>{}
  if(!changedSnapshot.exists()){
      console.log("Nó não encontrado")
      process.exit(0)
  }
  console.log(++count)
  console.table(changedSnapshot.val())
});
