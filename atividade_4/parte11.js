use('Empregos')

db.empregos.aggregate([
  {
    $match: {
      ano: {$in: [2018,2019,2020,2021]}
    }
  },
  {
    $group: {
      _id: {setor: "$subsetor", ano: "$ano"},
      totalEmpregos : {$sum: "$empregos"}
    }
  }
])
  