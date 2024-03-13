// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
//import { getAnalytics } from "firebase/analytics";
import { getDatabase, set, ref, push } from "firebase/database";
import { apiFirebaseKey } from "./firebase_credentials.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional

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

// Initialize Firebase
const app = initializeApp(firebaseConfig);
//const analytics = getAnalytics(app);
const db = getDatabase();

let newUserID = 1;
let refNode = ref(db, `users/${newUserID}`);
let newUserData = { email: "fulano@ifsul.edu.br", username: "fulano" };
set(refNode, newUserData);

const newUser = {
  email: "beltrano@ifsul.edu.br",
  username: "beltrano",
};
push(ref(db, "users/"), newUser);

console.log("Processamento terminado");
