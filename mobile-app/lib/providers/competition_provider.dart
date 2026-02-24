import 'package:flutter/material.dart';
import '../models/index.dart';
import '../services/api_service.dart';
import '../config/service_locator.dart';

class CompetitionProvider extends ChangeNotifier {
  final _apiService = getIt<ApiService>();

  List<Competition> _competitions = [];
  Competition? _selectedCompetition;
  bool _isLoading = false;
  String? _errorMessage;

  List<Competition> get competitions => _competitions;
  Competition? get selectedCompetition => _selectedCompetition;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  Future<void> loadCompetitions({int page = 1}) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      _competitions = await _apiService.getCompetitions(page: page);

      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> loadCompetitionDetail(int id) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      _selectedCompetition = await _apiService.getCompetitionDetail(id);

      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> createCompetition({
    required String title,
    required String description,
  }) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      final competition = await _apiService.createCompetition(
        title: title,
        description: description,
      );

      _competitions.insert(0, competition);

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

  Future<bool> updateCompetition({
    required int id,
    required String title,
    required String description,
  }) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      final updated = await _apiService.updateCompetition(
        id: id,
        title: title,
        description: description,
      );

      // Update in list
      final index = _competitions.indexWhere((c) => c.id == id);
      if (index != -1) {
        _competitions[index] = updated;
      }
      _selectedCompetition = updated;

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

  Future<bool> deleteCompetition(int id) async {
    try {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();

      await _apiService.deleteCompetition(id);
      _competitions.removeWhere((c) => c.id == id);

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
