import 'package:flutter/material.dart';
import '../models/competition.dart';
import '../services/api_service.dart';

class CompetitionProvider extends ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  List<Competition> _competitions = [];
  bool _isLoading = false;
  String? _errorMessage;

  List<Competition> get competitions => _competitions;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  Future<void> fetchCompetitions() async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      _competitions = await _apiService.getCompetitions();
      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _errorMessage = 'Error: ${e.toString()}';
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<Competition?> fetchCompetitionDetail(int id) async {
    try {
      return await _apiService.getCompetitionDetail(id);
    } catch (e) {
      _errorMessage = 'Error: ${e.toString()}';
      notifyListeners();
      return null;
    }
  }
}
