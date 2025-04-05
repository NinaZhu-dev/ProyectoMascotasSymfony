<?php

namespace App\Controller;

use App\Entity\Mascota;
use App\Repository\MascotaRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class QRController extends AbstractController{
    
    
    #[Route('/generar/qr/{id}', name: 'api_qr_generar', methods: ['POST'])]
    public function generarQr(Mascota $mascota, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($mascota->getIdUsuario() !== $this->getUser()) {
            return $this->json([
                'status' => 'error',
                'mensaje' => 'No puedes generar un QR para una mascota que no es tuya.',
            ], 403);
        }

        
        $qr = $this->generarQrParaMascota($mascota, $entityManager);
        $entityManager->flush();

        return $this->json([
            'status' => 'success',
            'mensaje' => 'Código QR generado correctamente',
            'qr' => $qr,
        ], 201, [], ['groups' => 'qr:read']);
    }

    #[Route('/mostrar/qr/{id}', name: 'api_qr_mostrar', methods: ['GET'])]
    public function mostrarQr(int $id, MascotaRepository $mascotaRepo): JsonResponse
    {
        $mascota = $mascotaRepo->find($id);

        if (!$mascota) {
            return $this->json(['mensaje' => 'Mascota no encontrada'], 404);
        }

        $usuario = $mascota->getIdUsuario();

        return $this->json([
            'nombre_mascota' => $mascota->getNombre(),
            'num_chip' => $mascota->getNumChip(),
            'observaciones' => $mascota->getObservaciones(),
            'foto' => $mascota->getFoto(),
            'nombre_usuario' => $usuario->getNombre(),
            'telefono_usuario' => $usuario->getTelefono(),
            'email_usuario' => $usuario->getEmail(),
        ]);
    }

    private function generar_qr(){

    }


}
