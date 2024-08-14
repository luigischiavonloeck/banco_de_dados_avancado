use("Empregos");

db.empregos.aggregate([
  {
    $match: {
      ano: { $in: [2018, 2021] },
    },
  },
  {
    $group: {
      _id: { setor: "$subsetor", ano: "$ano" },
      totalEmpregos: { $sum: "$empregos" },
    },
  },
  {
    $group: {
      _id: "$_id.setor",
      empregos2018: {
        $sum: {
          $cond: {
            if: { $eq: ["$_id.ano", 2018] },
            then: "$totalEmpregos",
            else: 0,
          },
        },
      },
      empregos2021: {
        $sum: {
          $cond: {
            if: { $eq: ["$_id.ano", 2021] },
            then: "$totalEmpregos",
            else: 0,
          },
        },
      },
    },
  },
  {
    $project: {
      setor: "$_id",
      crescimentoPercentual: {
        $cond: {
          if: { $eq: ["$empregos2018", 0] },
          then: 0,
          else: {
            $multiply: [
              {
                $divide: [
                  { $subtract: ["$empregos2021", "$empregos2018"] },
                  "$empregos2018",
                ],
              },
              100,
            ],
          },
        },
      },
    },
  },
  {
    $sort: { crescimentoPercentual: -1 },
  },
]);
