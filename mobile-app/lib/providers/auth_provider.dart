import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../models/index.dart';
import '../services/api_service.dart';
import '../services/secure_storage_service.dart';
import '../config/service_locator.dart';

class AuthProvider extends ChangeNotifier {
  final _apiService = getIt<ApiService>();
  final _storageService = getIt<SecureStorageService>();

  User? _user;
  String? _token;
  bool _isAuthenticating = false;
  String? _errorMessage;

  User? get user => _user;
  String? get token => _token;
  bool get isAuthenticating => _isAuthenticating;
  bool get isAuthenticated => _token != null && _user != null;
  String? get errorMessage => _errorMessage;

  AuthProvider() {
    _initializeAuth();
  }

  Future<void> _initializeAuth() async {
    final token = await _storageService.getToken();
    if (token != null) {
      _token = token;
      _apiService.setToken(token);
      try {
        _user = await _apiService.getProfile();
        notifyListeners();
      } catch (e) {
        await logout();
      }
    }
  }

  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      _isAuthenticating = true;
      _errorMessage = null;
      notifyListeners();

      await _apiService.register(
        name: name,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
      );

      _isAuthenticating = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      _isAuthenticating = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> login({
    required String email,
    required String password,
  }) async {
    try {
      _isAuthenticating = true;
      _errorMessage = null;
      notifyListeners();

      final response = await _apiService.login(email: email, password: password);

      _token = response['data']['token'];
      _apiService.setToken(_token!);
      await _storageService.saveToken(_token!);

      _user = User.fromJson(response['data']['user']);

      _isAuthenticating = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      _isAuthenticating = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> logout() async {
    try {
      await _apiService.logout();
    } catch (e) {
      // Still proceed with logout even if API call fails
    }

    _token = null;
    _user = null;
    _apiService.clearToken();
    await _storageService.deleteToken();
    notifyListeners();
    return true;
  }

  void clearErrorMessage() {
    _errorMessage = null;
    notifyListeners();
  }
}
