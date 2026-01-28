class Competition {
  final int id;
  final String name;
  final String description;
  final String startDate;
  final String endDate;
  final String? location;
  final int? maxParticipants;
  final String? poster;
  final String? guidebook;
  final String? createdAt;

  Competition({
    required this.id,
    required this.name,
    required this.description,
    required this.startDate,
    required this.endDate,
    this.location,
    this.maxParticipants,
    this.poster,
    this.guidebook,
    this.createdAt,
  });

  factory Competition.fromJson(Map<String, dynamic> json) {
    return Competition(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      startDate: json['start_date'] ?? '',
      endDate: json['end_date'] ?? '',
      location: json['location'],
      maxParticipants: json['max_participants'],
      poster: json['poster'],
      guidebook: json['guidebook'],
      createdAt: json['created_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'start_date': startDate,
      'end_date': endDate,
      'location': location,
      'max_participants': maxParticipants,
      'poster': poster,
      'guidebook': guidebook,
      'created_at': createdAt,
    };
  }
}
