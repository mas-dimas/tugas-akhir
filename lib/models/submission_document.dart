class SubmissionDocument {
  final int id;
  final int registrationId;
  final int documentTemplateId;
  final String filePath;
  final String status; // submitted, accepted, needs_revision
  final String? reviewerNotes;
  final String? createdAt;

  SubmissionDocument({
    required this.id,
    required this.registrationId,
    required this.documentTemplateId,
    required this.filePath,
    required this.status,
    this.reviewerNotes,
    this.createdAt,
  });

  factory SubmissionDocument.fromJson(Map<String, dynamic> json) {
    return SubmissionDocument(
      id: json['id'] ?? 0,
      registrationId: json['registration_id'] ?? 0,
      documentTemplateId: json['document_template_id'] ?? 0,
      filePath: json['file_path'] ?? '',
      status: json['status'] ?? 'submitted',
      reviewerNotes: json['reviewer_notes'],
      createdAt: json['created_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'registration_id': registrationId,
      'document_template_id': documentTemplateId,
      'file_path': filePath,
      'status': status,
      'reviewer_notes': reviewerNotes,
      'created_at': createdAt,
    };
  }
}
