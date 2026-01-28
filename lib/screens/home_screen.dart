import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../providers/competition_provider.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    super.initState();
    _loadCompetitions();
  }

  void _loadCompetitions() {
    context.read<CompetitionProvider>().fetchCompetitions();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Perlombaan'),
        backgroundColor: Colors.blue,
        foregroundColor: Colors.white,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () {
              _showLogoutDialog(context);
            },
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          _loadCompetitions();
        },
        child: Consumer2<CompetitionProvider, AuthProvider>(
          builder: (context, competitionProvider, authProvider, _) {
            if (competitionProvider.isLoading) {
              return const Center(child: CircularProgressIndicator());
            }

            if (competitionProvider.competitions.isEmpty) {
              return const Center(
                child: Text('Tidak ada perlombaan yang tersedia'),
              );
            }

            return ListView.builder(
              itemCount: competitionProvider.competitions.length,
              padding: const EdgeInsets.all(16),
              itemBuilder: (context, index) {
                final competition = competitionProvider.competitions[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 16),
                  child: ListTile(
                    title: Text(competition.name),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const SizedBox(height: 8),
                        Text(
                          competition.description,
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 8),
                        Text(
                          'Mulai: ${competition.startDate}',
                          style: const TextStyle(fontSize: 12),
                        ),
                      ],
                    ),
                    trailing: const Icon(Icons.arrow_forward_ios),
                    onTap: () {
                      // TODO: Navigate to competition detail
                    },
                  ),
                );
              },
            );
          },
        ),
      ),
    );
  }

  void _showLogoutDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Logout'),
        content: const Text('Apakah Anda yakin ingin logout?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () {
              context.read<AuthProvider>().logout();
              Navigator.of(context).pushReplacementNamed('/login');
            },
            child: const Text('Logout'),
          ),
        ],
      ),
    );
  }
}
