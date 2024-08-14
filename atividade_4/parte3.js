use('Empregos')

db.empregos.aggregate([
  {
    $match: {
      regiao: 'Pelotas'
    }
  },
  {
    $group: {
      _id: null,
      total: { $sum: { $multiply: ['$salariomedio', '$empregos'] } },
      totalEmp: { $sum: '$empregos' }
    }
    // (salario x empregos)/total empregos
  },
  {
    $project: {
      _id: 0,
      salariomedio: { $divide: ['$total', '$totalEmp'] }
    }
  }
])