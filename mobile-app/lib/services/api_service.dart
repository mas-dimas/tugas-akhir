import 'package:http/http.dart' as http;
import 'dart:convert';
import '../models/index.dart';

class ApiService {
  // Change this to your Laravel backend URL
  static const String baseUrl = 'http://10.0.2.2:8000/api'; // For Android emulator
  // static const String baseUrl = 'http://localhost:8000/api'; // For iOS simulator
  
  String? _token;

  void setToken(String token) {
    _token = token;
  }

  void clearToken() {
    _token = null;
  }

  Map<String, String> _getHeaders({bool requireAuth = false}) {
    final headers = {'Content-Type': 'application/json', 'Accept': 'application/json'};
    if (requireAuth && _token != null) {
      headers['Authorization'] = 'Bearer $_token';
    }
    return headers;
  }

  // ==================== AUTH ====================
  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/register'),
        headers: _getHeaders(),
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        }),
      );

      if (response.statusCode == 201) {
        return jsonDecode(response.body);
      } else {
        throw Exception(jsonDecode(response.body)['message'] ?? 'Registration failed');
      }
    } catch (e) {
      rethrow;
    }
  }

  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: _getHeaders(),
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        _token = data['data']['token'];
        return data;
      } else {
        throw Exception(jsonDecode(response.body)['message'] ?? 'Login failed');
      }
    } catch (e) {
      rethrow;
    }
  }

  Future<bool> logout() async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/logout'),
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        _token = null;
        return true;
      }
      return false;
    } catch (e) {
      rethrow;
    }
  }

  Future<User> getProfile() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/profile'),
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return User.fromJson(data['data']);
      }
      throw Exception('Failed to get profile');
    } catch (e) {
      rethrow;
    }
  }

  // ==================== COMPETITIONS ====================
  Future<List<Competition>> getCompetitions({int page = 1}) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/competitions?page=$page'),
        headers: _getHeaders(),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final competitions = (data['data'] as List)
            .map((item) => Competition.fromJson(item))
            .toList();
        return competitions;
      }
      throw Exception('Failed to load competitions');
    } catch (e) {
      rethrow;
    }
  }

  Future<Competition> getCompetitionDetail(int id) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/competitions/$id'),
        headers: _getHeaders(),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return Competition.fromJson(data['data']);
      }
      throw Exception('Competition not found');
    } catch (e) {
      rethrow;
    }
  }

  Future<Competition> createCompetition({
    required String title,
    required String description,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/competitions'),
        headers: _getHeaders(requireAuth: true),
        body: jsonEncode({
          'title': title,
          'description': description,
        }),
      );

      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        return Competition.fromJson(data['data']);
      }
      throw Exception('Failed to create competition');
    } catch (e) {
      rethrow;
    }
  }

  Future<Competition> updateCompetition({
    required int id,
    required String title,
    required String description,
  }) async {
    try {
      final response = await http.put(
        Uri.parse('$baseUrl/competitions/$id'),
        headers: _getHeaders(requireAuth: true),
        body: jsonEncode({
          'title': title,
          'description': description,
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return Competition.fromJson(data['data']);
      }
      throw Exception('Failed to update competition');
    } catch (e) {
      rethrow;
    }
  }

  Future<bool> deleteCompetition(int id) async {
    try {
      final response = await http.delete(
        Uri.parse('$baseUrl/competitions/$id'),
        headers: _getHeaders(requireAuth: true),
      );

      return response.statusCode == 200;
    } catch (e) {
      rethrow;
    }
  }

  // ==================== REGISTRATIONS ====================
  Future<List<Registration>> getMyRegistrations({int page = 1}) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/registrations?page=$page'),
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final registrations = (data['data'] as List)
            .map((item) => Registration.fromJson(item))
            .toList();
        return registrations;
      }
      throw Exception('Failed to load registrations');
    } catch (e) {
      rethrow;
    }
  }

  Future<Registration> getRegistrationDetail(int id) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/registrations/$id'),
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return Registration.fromJson(data['data']);
      }
      throw Exception('Registration not found');
    } catch (e) {
      rethrow;
    }
  }

  Future<Registration> registerCompetition(int competitionId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/registrations'),
        headers: _getHeaders(requireAuth: true),
        body: jsonEncode({'competition_id': competitionId}),
      );

      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        return Registration.fromJson(data['data']);
      }
      throw Exception(jsonDecode(response.body)['message'] ?? 'Registration failed');
    } catch (e) {
      rethrow;
    }
  }

  Future<bool> cancelRegistration(int id) async {
    try {
      final response = await http.delete(
        Uri.parse('$baseUrl/registrations/$id'),
        headers: _getHeaders(requireAuth: true),
      );

      return response.statusCode == 200;
    } catch (e) {
      rethrow;
    }
  }

  Future<List<Registration>> getAllRegistrations({int page = 1}) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/admin/registrations?page=$page'),
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final registrations = (data['data'] as List)
            .map((item) => Registration.fromJson(item))
            .toList();
        return registrations;
      }
      throw Exception('Failed to load registrations');
    } catch (e) {
      rethrow;
    }
  }
}
