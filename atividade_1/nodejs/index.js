import { initializeApp } from "firebase/app";
import { getDatabase, set, ref, update } from "firebase/database";
import { apiFirebaseKey } from "./firebase_credentials.js";
import path from "path";

const firebaseConfig = {
  apiKey: apiFirebaseKey,
  authDomain: "firstproject-24838.firebaseapp.com",
  databaseURL: "https://firstproject-24838-default-rtdb.firebaseio.com",
  projectId: "firstproject-24838",
  storageBucket: "firstproject-24838.appspot.com",
  messagingSenderId: "304374519726",
  appId: "1:304374519726:web:2fbe06b303fb0464c3da3f",
  measurementId: "G-H3BQT4E7KL",
};

const app = initializeApp(firebaseConfig);
const db = getDatabase();

const params = [
  { id: 1, name: "Maria", email: "maria@gmail.com", idade: 15 },
  { id: 2, name: "Joao", email: "joao@gmail.com", idade: 25 },
  { id: 3, name: "ana", email: "ana@gmail.com", idade: 23 },
];

params.forEach((param) => {
  set(ref(db, `users/${param.id}`), param);
});

update(ref(db, `users/1`), { name: "Maria Silva" });

update(ref(db, `users/2`), { name: "Joao Gomes", idade: 35 });

console.log("deu certo");
