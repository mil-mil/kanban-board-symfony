<?php

namespace App\Controller;

use App\Entity\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class KanbanBoardController extends AbstractController
{
    /**
     * @Route("/get-state", name="get_state")
     * @return JsonResponse
     */
    public function getStateAction()
    {
        $data = [];

        $em = $this->getDoctrine()->getManager();
        $columns = $em->getRepository('App:Column')->findAllOrderedByPosition();

        foreach ($columns as $column)
        {
            $cards = $em->getRepository('App:Card')->findAllOrderedByPosition($column->getId());
            $cardIds = [];

            foreach ($cards as $card)
            {
                $cardIds[] = $card->getId();
            }

            $data['columns'][$column->getId()] = [
                'id' => $column->getId(),
                'name' => $column->getTitle(),
                'maxCards' => $column->getCapacity(),
                'cardIds' => $cardIds
            ];
        }

        $cards = $em->getRepository('App:Card')->findAll();
        foreach ($cards as $card)
        {
            $data['cards'][$card->getId()] = ['id' => $card->getId(), 'title' => 'proba', 'content' => $card->getContent()];
        }

        return $this->json($data);
    }

    /**
     * @Route("card/new", name="new_card")
     * @param Request $request
     * @return JsonResponse
     */
    public function addNewCardAction(Request $request)
    {
        $content = $request->getContent();
        $json = json_decode($content);

        $repo = $this->getDoctrine()->getRepository('App:Column');
        $allCards = $this->getDoctrine()->getRepository('App:Card')->findBy(['inColumn' => $json->columnId]);

        foreach ($allCards as $card)
        {
            $currentPosition = $card->getPosition();
            $card->setPosition(++$currentPosition);
        }

        $column = $repo->findOneBy(['id' => $json->columnId]);

        $card = new Card();
        $card->setId($json->cardId);
        $card->setContent($json->content);
        $card->setPosition(0);
        $card->setInColumn($column);

        $em = $this->getDoctrine()->getManager();
        $em->persist($card);
        $em->flush();

        return $this->json(['success' => true]);
    }

    /**
     * @Route("card/reorder", name="reorder_card")
     * @param Request $request
     * @return JsonResponse
     */
    public function reorderAction(Request $request)
    {
        $content = $request->getContent();
        $cardsData = json_decode($content);

        $em = $this->getDoctrine()->getManager();

        foreach ($cardsData as $data)
        {
            $card = $em->getRepository('App:Card')->findOneBy(['id' => $data->cardId]);
            $column = $em->getRepository('App:Column')->findOneBy(['id' => $data->columnId]);
            $card->setPosition($data->position);
            $card->setInColumn($column);
        }
        $em->flush();

        return $this->json([
            'sucess' => true
        ]);
    }

    /**
     * @Route("column/capacity", name="column_capacity")
     * @param Request $request
     * @return JsonResponse
     */
    public function setCapacityAction(Request $request)
    {
        $content = $request->getContent();
        $columnData = json_decode($content);

        $em = $this->getDoctrine()->getManager();

        $column = $em->getRepository('App:Column')->findOneBy(['id' => $columnData->columnId]);
        $column->setCapacity($columnData->capacity);
        $em->flush();

        return $this->json([
            'sucess' => true
        ]);
    }

    /**
     * @Route("card/delete", name="card_delete")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCardAction(Request $request)
    {
        $content = $request->getContent();
        $cardData = json_decode($content);

        $em = $this->getDoctrine()->getManager();

        $card = $em->getRepository('App:Card')->findOneBy(['id' => $cardData->cardId]);
        $em->remove($card);
        $em->flush();

        return $this->json([
            'sucess' => true
        ]);
    }
}
