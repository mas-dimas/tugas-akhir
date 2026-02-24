class Registration {
  final int id;
  final int userId;
  final int competitionId;
  final String status;
  final DateTime createdAt;
  final DateTime updatedAt;

  Registration({
    required this.id,
    required this.userId,
    required this.competitionId,
    required this.status,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Registration.fromJson(Map<String, dynamic> json) {
    return Registration(
      id: json['id'],
      userId: json['user_id'],
      competitionId: json['competition_id'],
      status: json['status'] ?? 'pending',
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'competition_id': competitionId,
      'status': status,
    };
  }
}
