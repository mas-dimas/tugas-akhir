import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'config/service_locator.dart';
import 'providers/auth_provider.dart';
import 'providers/competition_provider.dart';
import 'providers/registration_provider.dart';
import 'screens/auth/login_screen.dart';
import 'screens/auth/register_screen.dart';
import 'screens/competitions/competitions_list_screen.dart';
import 'screens/competitions/competition_detail_screen.dart';

void main() {
  setupServiceLocator();
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => CompetitionProvider()),
        ChangeNotifierProvider(create: (_) => RegistrationProvider()),
      ],
      child: MaterialApp(
        title: 'Tugas Akhir - Perlombaan',
        theme: ThemeData(
          useMaterial3: true,
          colorScheme: ColorScheme.fromSeed(
            seedColor: const Color(0xFF2196F3),
            brightness: Brightness.light,
          ),
          appBarTheme: const AppBarTheme(
            centerTitle: true,
            elevation: 1,
          ),
        ),
        home: const _AppHome(),
        routes: {
          '/login': (context) => const LoginScreen(),
          '/register': (context) => const RegisterScreen(),
        },
        onGenerateRoute: (settings) {
          if (settings.name?.startsWith('/competitions/') ?? false) {
            final id = int.parse(settings.name!.replaceFirst('/competitions/', ''));
            return MaterialPageRoute(
              builder: (context) => CompetitionDetailScreen(competitionId: id),
            );
          }
          return null;
        },
      ),
    );
  }
}

class _AppHome extends StatelessWidget {
  const _AppHome({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Consumer<AuthProvider>(
      builder: (context, authProvider, _) {
        if (authProvider.isAuthenticated) {
          return const CompetitionsListScreen();
        } else {
          return const LoginScreen();
        }
      },
    );
  }
}
