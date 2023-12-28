<?php

namespace App\Twig\Components;

use App\Repository\EpisodeRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class LastEpisode
{
    private EpisodeRepository $episodeRepository;

    public function __construct(
        EpisodeRepository $episodeRepository
    ) {
        $this->episodeRepository=$episodeRepository;
    }

    public function getLastEpisodes(): array
    {
        return $this->episodeRepository->findBy([], ['id' => 'DESC'], 3);
    }
}
