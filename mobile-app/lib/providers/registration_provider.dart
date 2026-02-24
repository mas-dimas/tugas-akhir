import 'package:flutter/material.dart';
import '../models/index.dart';
import '../services/api_service.dart';
import '../config/service_locator.dart';

class RegistrationProvider extends ChangeNotifier {
  final _apiService = getIt<ApiService>();

  List<Registration> _myRegistrations = [];
  List<Registration> _allRegistrations = [];
  Registration? _selectedRegistration;
  bool _isLoading = false;
  String? _errorMessage;

  List<Registration> get myRegistrations => _myRegistrations;
  List<Registration> get allRegistrations => _allRegistrations;
  Registration? get selectedRegistration => _selectedRegistration;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  Future<void> loadMyRegistrations({int page = 1}) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      _myRegistrations = await _apiService.getMyRegistrations(page: page);

      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> loadAllRegistrations({int page = 1}) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      _allRegistrations = await _apiService.getAllRegistrations(page: page);

      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> loadRegistrationDetail(int id) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      _selectedRegistration = await _apiService.getRegistrationDetail(id);

      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> registerCompetition(int competitionId) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      final registration = await _apiService.registerCompetition(competitionId);
      _myRegistrations.insert(0, registration);

      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> cancelRegistration(int id) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      await _apiService.cancelRegistration(id);
      _myRegistrations.removeWhere((r) => r.id == id);

      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  void clearError() {
    _errorMessage = null;
    notifyListeners();
  }
}
