use("Empregos");

db.empregos.aggregate([
  {
    $group: {
      _id: "$subsetor",
      totalEmpregos: { $sum: "$empregos" },
    },
  },
  {
    $sort: {
      totalEmpregos: -1,
    },
  },
  {
    $limit: 5,
  },
]);
