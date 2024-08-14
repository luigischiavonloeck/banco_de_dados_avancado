use('Empregos')

db.empregos.aggregate([
  {
    $group: {
      _id: '$ano',
      total: { $sum: '$empregos' }
    }
  },
  {
    $sort: {
      _id: 1
    }
  }
])
