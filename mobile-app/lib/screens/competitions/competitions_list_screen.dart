import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../providers/competition_provider.dart';
import 'competition_detail_screen.dart';

class CompetitionsListScreen extends StatefulWidget {
  const CompetitionsListScreen({Key? key}) : super(key: key);

  @override
  State<CompetitionsListScreen> createState() => _CompetitionsListScreenState();
}

class _CompetitionsListScreenState extends State<CompetitionsListScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<CompetitionProvider>(context, listen: false)
          .loadCompetitions();
    });
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final isAdmin = authProvider.user?.role == 'admin';

    return Scaffold(
      appBar: AppBar(
        title: const Text('Kompetisi'),
        elevation: 0,
        actions: [
          if (isAdmin)
            IconButton(
              icon: const Icon(Icons.add),
              onPressed: () {
                // TODO: Navigate to create competition
              },
            ),
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              await authProvider.logout();
              if (mounted) {
                Navigator.of(context).pushReplacementNamed('/login');
              }
            },
          ),
        ],
      ),
      body: Consumer<CompetitionProvider>(
        builder: (context, competitionProvider, _) {
          if (competitionProvider.isLoading) {
            return const Center(child: CircularProgressIndicator());
          }

          if (competitionProvider.competitions.isEmpty) {
            return const Center(
              child: Text('Tidak ada kompetisi'),
            );
          }

          return RefreshIndicator(
            onRefresh: () =>
                competitionProvider.loadCompetitions(),
            child: ListView.builder(
              itemCount: competitionProvider.competitions.length,
              itemBuilder: (context, index) {
                final competition = competitionProvider.competitions[index];
                return Card(
                  margin:
                      const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  child: ListTile(
                    title: Text(competition.title),
                    subtitle: Text(competition.description,
                        maxLines: 2, overflow: TextOverflow.ellipsis),
                    onTap: () {
                      Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (_) => CompetitionDetailScreen(
                            competitionId: competition.id,
                          ),
                        ),
                      );
                    },
                  ),
                );
              },
            ),
          );
        },
      ),
    );
  }
}
