class Registration {
  final int id;
  final int userId;
  final int competitionId;
  final String status; // pending, approved, rejected
  final String? notes;
  final String? createdAt;
  final String? updatedAt;

  Registration({
    required this.id,
    required this.userId,
    required this.competitionId,
    required this.status,
    this.notes,
    this.createdAt,
    this.updatedAt,
  });

  factory Registration.fromJson(Map<String, dynamic> json) {
    return Registration(
      id: json['id'] ?? 0,
      userId: json['user_id'] ?? 0,
      competitionId: json['competition_id'] ?? 0,
      status: json['status'] ?? 'pending',
      notes: json['notes'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'competition_id': competitionId,
      'status': status,
      'notes': notes,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }
}
