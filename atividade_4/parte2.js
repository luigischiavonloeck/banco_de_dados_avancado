use('Empregos')

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
