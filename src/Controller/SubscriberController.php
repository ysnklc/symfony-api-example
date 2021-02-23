<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\SubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SubscriberController
 * @package App\Controller
 *
 * @Route(path="/api/subscriber")
 */
class SubscriberController extends AbstractController
{
    private $subscriberRepository;
    private $cityRepository;

    public function __construct(SubscriberRepository $subscriberRepository, CityRepository $cityRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
        $this->cityRepository = $cityRepository;
    }

    /**
     * @Route("/add", name="add_subscriber", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $phone = $data['phone'];
        $cityId = $data['cityId'];

        if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($cityId)) {
            return new JsonResponse(['status' => 'Zorunlu alanlar girilmelidir.'], Response::HTTP_NO_CONTENT);
        }

        $city = $this->cityRepository->findOneBy(['id' => $cityId]);
        $this->subscriberRepository->addSubscriber($firstName, $lastName, $email, $phone, $city);

        return new JsonResponse(['status' => 'Abone ekleme işlemi başarılı.'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="get_subscriber", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $subscriber= $this->subscriberRepository->findOneBy(['id' => $id]);

        if (empty($subscriber)) {
            return new JsonResponse(['status' => 'Abone bulunamadı.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $subscriber->getId(),
            'firstName' => $subscriber->getFirstName(),
            'lastName' => $subscriber->getLastName(),
            'email' => $subscriber->getEmail(),
            'phone' => $subscriber->getPhone(),
            'city' => $subscriber->getCity()->getName()
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/all", name="get_all_subscriber", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $subscribers = $this->subscriberRepository->findAll();
        $data = [];

        foreach ($subscribers as $subscriber) {
            $data[] = [
                'id' => $subscriber->getId(),
                'firstName' => $subscriber->getFirstName(),
                'lastName' => $subscriber->getLastName(),
                'email' => $subscriber->getEmail(),
                'phone' => $subscriber->getPhone(),
                'city' => $subscriber->getCity()->getName()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_subscriber", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $subscriber = $this->subscriberRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['firstName']) ? true : $subscriber->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $subscriber->setLastName($data['lastName']);
        empty($data['email']) ? true : $subscriber->setEmail($data['email']);
        empty($data['phone']) ? true : $subscriber->setPhone($data['phone']);
        empty($data['cityId']) ? true : $subscriber->setCity($this->cityRepository->findOneBy(['id' => $data['cityId']]));

        $updatedSubscriber = $this->subscriberRepository->updateSubscriber($subscriber);

        return new JsonResponse($updatedSubscriber->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/delete/{id}", name="delete_subscriber", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $subscriber = $this->subscriberRepository->findOneBy(['id' => $id]);

        if (empty($subscriber)) {
            return new JsonResponse(['status' => 'Abone bulunamadı.'], Response::HTTP_NOT_FOUND);
        }

        $this->subscriberRepository->removeSubscriberr($subscriber);

        return new JsonResponse(['status' => 'Abone silindi.'], Response::HTTP_OK);
    }
}
