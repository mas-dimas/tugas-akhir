import 'package:get_it/get_it.dart';
import '../services/api_service.dart';
import '../services/secure_storage_service.dart';

final getIt = GetIt.instance;

void setupServiceLocator() {
  // Register services
  getIt.registerSingleton<ApiService>(ApiService());
  getIt.registerSingleton<SecureStorageService>(SecureStorageService());
}
