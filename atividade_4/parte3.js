use('Empregos')

//Qual salário médio em Pelotas (Lembre-se que se uma área tem 100 empregados e outra 1 o salário de uma é 100x mais representativo que da outra) ?
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