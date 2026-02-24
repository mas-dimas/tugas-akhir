import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/competition_provider.dart';
import '../../providers/registration_provider.dart';

class CompetitionDetailScreen extends StatefulWidget {
  final int competitionId;

  const CompetitionDetailScreen({
    Key? key,
    required this.competitionId,
  }) : super(key: key);

  @override
  State<CompetitionDetailScreen> createState() =>
      _CompetitionDetailScreenState();
}

class _CompetitionDetailScreenState extends State<CompetitionDetailScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<CompetitionProvider>(context, listen: false)
          .loadCompetitionDetail(widget.competitionId);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Detail Kompetisi'),
        elevation: 0,
      ),
      body: Consumer<CompetitionProvider>(
        builder: (context, competitionProvider, _) {
          if (competitionProvider.isLoading) {
            return const Center(child: CircularProgressIndicator());
          }

          if (competitionProvider.selectedCompetition == null) {
            return const Center(
              child: Text('Kompetisi tidak ditemukan'),
            );
          }

          final competition = competitionProvider.selectedCompetition!;

          return SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if (competition.posterPath != null)
                  Image.network(
                    competition.posterPath!,
                    width: double.infinity,
                    height: 200,
                    fit: BoxFit.cover,
                    errorBuilder: (context, error, stackTrace) {
                      return Container(
                        width: double.infinity,
                        height: 200,
                        color: Colors.grey[300],
                        child: const Icon(Icons.image_not_supported),
                      );
                    },
                  ),
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        competition.title,
                        style: Theme.of(context).textTheme.headlineMedium,
                      ),
                      const SizedBox(height: 16),
                      Text(
                        'Deskripsi',
                        style: Theme.of(context).textTheme.titleMedium,
                      ),
                      const SizedBox(height: 8),
                      Text(
                        competition.description,
                        style: Theme.of(context).textTheme.bodyMedium,
                      ),
                      const SizedBox(height: 24),
                      Consumer<RegistrationProvider>(
                        builder: (context, registrationProvider, _) {
                          return ElevatedButton(
                            onPressed: registrationProvider.isLoading
                                ? null
                                : () async {
                                    final success = await registrationProvider
                                        .registerCompetition(competition.id);
                                    if (success && mounted) {
                                      ScaffoldMessenger.of(context)
                                          .showSnackBar(
                                        const SnackBar(
                                          content: Text(
                                              'Pendaftaran berhasil!'),
                                          backgroundColor: Colors.green,
                                        ),
                                      );
                                      Navigator.of(context).pop();
                                    }
                                  },
                            child: registrationProvider.isLoading
                                ? const SizedBox(
                                    height: 20,
                                    width: 20,
                                    child: CircularProgressIndicator(
                                      strokeWidth: 2,
                                    ),
                                  )
                                : const Text('Daftar'),
                          );
                        },
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
