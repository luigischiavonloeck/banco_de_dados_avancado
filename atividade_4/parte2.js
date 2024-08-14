use('Empregos')

//Quanto ganham todos os tecnólogos em TI no RS juntos em 2021?
db.empregos.aggregate([
  {
    $match: {
      ano: 2021
    }
  },
  {
    $group: {
      _id: null,
      total: { $sum: '$salariomedio' }
    }
  }
])
