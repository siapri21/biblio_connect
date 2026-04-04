<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/bibliotheque/export')]
#[IsGranted('ROLE_LIBRARIAN')]
class StaffExportController extends AbstractController
{
    #[Route('/catalogue.csv', name: 'app_staff_export_catalog', methods: ['GET'])]
    public function catalogCsv(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        $response = new StreamedResponse(function () use ($books): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fputcsv($out, ['id', 'title', 'author', 'category', 'language', 'stock', 'library', 'city'], ';');
            foreach ($books as $b) {
                $lib = $b->getLibrary();
                fputcsv($out, [
                    $b->getId(),
                    $b->getTitle(),
                    $b->getAuthor(),
                    $b->getCategory(),
                    $b->getLanguage(),
                    $b->getStock(),
                    $lib?->getName() ?? '',
                    $lib?->getCity() ?? '',
                ], ';');
            }
            fclose($out);
        });
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="catalogue-biblio-connect.csv"');

        return $response;
    }

    #[Route('/reservations.csv', name: 'app_staff_export_reservations', methods: ['GET'])]
    public function reservationsCsv(ReservationRepository $reservationRepository): Response
    {
        $rows = $reservationRepository->findAllForStaffOrdered(null);

        $response = new StreamedResponse(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fputcsv($out, ['id', 'member_email', 'book', 'status', 'start', 'end', 'extensions', 'created'], ';');
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->getId(),
                    $r->getMember()?->getUserIdentifier() ?? '',
                    $r->getBook()?->getTitle() ?? '',
                    $r->getStatus(),
                    $r->getStartAt()->format('Y-m-d'),
                    $r->getEndAt()->format('Y-m-d'),
                    $r->getExtensionCount(),
                    $r->getCreatedAt()->format('Y-m-d H:i'),
                ], ';');
            }
            fclose($out);
        });
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="reservations-biblio-connect.csv"');

        return $response;
    }
}
