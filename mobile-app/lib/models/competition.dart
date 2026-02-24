class Competition {
  final int id;
  final String title;
  final String description;
  final String? posterPath;
  final DateTime createdAt;
  final DateTime updatedAt;

  Competition({
    required this.id,
    required this.title,
    required this.description,
    this.posterPath,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Competition.fromJson(Map<String, dynamic> json) {
    return Competition(
      id: json['id'],
      title: json['title'],
      description: json['description'],
      posterPath: json['poster_path'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'poster_path': posterPath,
    };
  }
}
