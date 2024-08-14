use('Empregos')

//Qual região tinha mais empregos em 2021?

db.empregos.aggregate([
  {
    $match: {
      ano: 2021
    }
  },
  {
    $group: {
      _id: "$regiao",
      totalEmpregos: {
        $sum: "$empregos"
      }
    }
  },
  {
    $sort: {
      totalEmpregos: -1
    }
  },
  {
    $limit: 1
  }
]) 
