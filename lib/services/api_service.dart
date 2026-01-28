import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';
import '../models/competition.dart';
import '../models/registration.dart';
import '../models/submission_document.dart';

class ApiService {
  // Base URL - Ubah sesuai dengan server Anda
  static const String baseUrl = 'http://localhost:8000/api';
  
  late String? _token;

  ApiService() {
    _loadToken();
  }

  Future<void> _loadToken() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
  }

  Future<Map<String, String>> _getHeaders() async {
    Map<String, String> headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    
    if (_token != null) {
      headers['Authorization'] = 'Bearer $_token';
    }
    
    return headers;
  }

  // ==========================================
  // AUTH ENDPOINTS
  // ==========================================

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/login'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success'] && data['data']['token'] != null) {
          _token = data['data']['token'];
          
          // Save token ke SharedPreferences
          final prefs = await SharedPreferences.getInstance();
          await prefs.setString('auth_token', _token!);
          
          return {
            'success': true,
            'user': User.fromJson(data['data']['user']),
            'token': _token,
          };
        }
      }
      
      return {
        'success': false,
        'message': 'Email atau password salah',
      };
    } catch (e) {
      return {
        'success': false,
        'message': 'Error: ${e.toString()}',
      };
    }
  }

  Future<Map<String, dynamic>> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/register'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        }),
      );

      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          _token = data['data']['token'];
          
          // Save token ke SharedPreferences
          final prefs = await SharedPreferences.getInstance();
          await prefs.setString('auth_token', _token!);
          
          return {
            'success': true,
            'user': User.fromJson(data['data']['user']),
            'token': _token,
          };
        }
      }
      
      return {
        'success': false,
        'message': 'Registrasi gagal',
      };
    } catch (e) {
      return {
        'success': false,
        'message': 'Error: ${e.toString()}',
      };
    }
  }

  Future<void> logout() async {
    try {
      final headers = await _getHeaders();
      await http.post(
        Uri.parse('$baseUrl/auth/logout'),
        headers: headers,
      );
    } catch (e) {
      print('Logout error: $e');
    }
    
    _token = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  // ==========================================
  // COMPETITION ENDPOINTS
  // ==========================================

  Future<List<Competition>> getCompetitions() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/competitions'),
        headers: await _getHeaders(),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          List<Competition> competitions = [];
          for (var item in data['data']) {
            competitions.add(Competition.fromJson(item));
          }
          return competitions;
        }
      }
      
      return [];
    } catch (e) {
      print('Error fetching competitions: $e');
      return [];
    }
  }

  Future<Competition?> getCompetitionDetail(int id) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/competitions/$id'),
        headers: await _getHeaders(),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return Competition.fromJson(data['data']);
        }
      }
      
      return null;
    } catch (e) {
      print('Error fetching competition detail: $e');
      return null;
    }
  }

  // ==========================================
  // REGISTRATION ENDPOINTS
  // ==========================================

  Future<List<Registration>> getUserRegistrations() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/registrations'),
        headers: await _getHeaders(),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          List<Registration> registrations = [];
          for (var item in data['data']) {
            registrations.add(Registration.fromJson(item));
          }
          return registrations;
        }
      }
      
      return [];
    } catch (e) {
      print('Error fetching registrations: $e');
      return [];
    }
  }

  Future<Map<String, dynamic>> registerToCompetition(int competitionId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/registrations'),
        headers: await _getHeaders(),
        body: jsonEncode({
          'competition_id': competitionId,
        }),
      );

      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return {
            'success': true,
            'registration': Registration.fromJson(data['data']),
          };
        }
      }
      
      return {
        'success': false,
        'message': 'Pendaftaran gagal',
      };
    } catch (e) {
      return {
        'success': false,
        'message': 'Error: ${e.toString()}',
      };
    }
  }

  // ==========================================
  // USER ENDPOINTS
  // ==========================================

  Future<User?> getCurrentUser() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/user'),
        headers: await _getHeaders(),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return User.fromJson(data);
      }
      
      return null;
    } catch (e) {
      print('Error fetching current user: $e');
      return null;
    }
  }
}
