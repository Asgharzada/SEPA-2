<?php
namespace Acme\SepaBlogBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\SepaBlogBundle\Entity\Visitors;
use Acme\SepaBlogBundle\Entity\Game1D;
use Acme\SepaBlogBundle\Entity\Game;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session;

class DefaultController extends Controller{
	
	public function indexAction()
	{
		return $this->render('AcmeSepaBlogBundle:Blog:index.html.twig');
	}
	public function aTopRecordsAction($level){
	$repository = $this->getDoctrine()
	->getRepository('AcmeSepaBlogBundle:Visitors');
	$query = $repository->createQueryBuilder('p')
	->orderBy('p.'.$level,'DESC')
	->setMaxResults(10)
	->getQuery();
   return 	$visitors = $query->getResult();
	//return $this->render('AcmeSepaBlogBundle:Default:TopList.html.twig', array('visitors' => $visitors));
	}


	
	//Start Function, Login
    public function createAction(Request $request){	
	$Visitor = new Visitors();
    	$form = $this->createFormBuilder($Visitor)
            ->add('name', 'text',array('label'  => 'Name'))
	    ->getForm();

	if ($this->getRequest()->getMethod() == 'POST') {	
		$form->bindRequest($this->getRequest());
		//if ($form->isValid()) {	
		$session = $this->getRequest()->getSession();
						
		$session->set('score', 100);
		$session->set('chance', 10);
		$session->set('chance2', 15);
		$session->set('chance3', 20);	
		$session->set('chance3', 20);
		$session->set('name', $form->getName());
		return $this->redirect($this->generateUrl('type'));
		}
		return $this->render('AcmeSepaBlogBundle:Default:new.html.twig', array('form' => $form->createView()));
 	} 	 	

	public function typeAction(Request $request){
		$session = $this->getRequest()->getSession();
		$ran1 = rand(1,10);
		$ran2 = rand(1,10);
		$ran3 = rand(1,10);	
		$session->set('first', $ran1);
		$session->set('second', $ran2);
		$session->set('third', $ran3);   
        	$Game = new Game();
	   $form = $this->createFormBuilder($Game)
	   ->add('type','choice',array('choices' => array('1D' => '1D', '2D' => '2D', '3D' => '3D'),'required' => true,'expanded' => true,))
	   ->getForm();
		$url1d= $this->generateUrl('Game1D');
		$url2d=$this->generateUrl('Game2D');
		$url3D=$this->generateUrl('Game3D');
	   if($this->getRequest()->getMethod()=='POST'){
		$form->bindRequest($request);
			if($Game->getType()=='1D'){
				 $this->generateUrl('Game1D');
				//return $this->redirect($this->generateUrl('Game1D'));	
			}
			if($Game->getType()=='2D'){ 
				return $this->redirect($this->generateUrl('Game2D'));
			}
			if($Game->getType()=='3D'){ 
				return $this->redirect($this->generateUrl('Game3D'));
			}
	}
	return $this->render('AcmeSepaBlogBundle:Default:type.html.twig', array('D1'=>$url1d,'D2'=>$url2d,'D3'=>$url3D,'form' => $form->createView()));	
	}
	public function lostAction(){
		
		return $this->render('AcmeSepaBlogBundle:Default:lost.html.twig',array('url'=>$this->generateUrl('type')));
	}

	//Game in forms


	public function D1Action(Request $request){
		$session = $this->getRequest()->getSession();	
		$first = $session->get('first');
		$score = $session->get('score');
		$chance = $session->get('chance');
		//$score = 100;	
		$Game = new Game();
		$form = $this->createFormBuilder($Game)
	->add('guess','text')
	->getForm();
		$url1d= $this->generateUrl('Game1D');
		$url2d=$this->generateUrl('Game2D');
		$url3D=$this->generateUrl('Game3D');
		if($this->getRequest()->getMethod()=='POST'){
					$form->bindRequest($request);
					$guess = $Game->getGuess();
					$session = $this->getRequest()->getSession();
					if($session->get('chance')==0){
						
						$session->set('chance', 10);
			return $this->render('AcmeSepaBlogBundle:Default:lost.html.twig',array('url'=>$this->generateUrl('type')));
						
					}
					if($first == $guess){
						$Game->setGuess('');
						$Game->setHint('You won!');
						$session->set('score', $score-10);			
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));

					}elseif ($first < $guess){
						$Game->setGuess('');
						$Game->setHint('Lower!');
						$session->set('score', $score-10);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));

					}elseif($first > $guess){
						$Game->setGuess('');
						$Game->setHint('Higher!');
						$session->set('score', $score-10);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
						}
			
					$form = $this->createFormBuilder($Game)
					->add('guess','text')
					->add('chance','text',array('read_only' => true))
					->add('hint','text', array('read_only' => true))
					->add('score','text', array('read_only' => true))
					->getForm();
			
		return $this->render('AcmeSepaBlogBundle:Default:game.html.twig', array('D1'=>$url1d,'D2'=>$url2d,'D3'=>$url3D,'visitors'=>$this->aTopRecordsAction("first"),'form' => $form->createView()));
	     }

	return $this->render('AcmeSepaBlogBundle:Default:game.html.twig', array('D1'=>$url1d,'D2'=>$url2d,'D3'=>$url3D,'visitors'=>$this->aTopRecordsAction("first"),'form' => $form->createView()));

}





		public function D2Action(Request $request){
			$session = $this->getRequest()->getSession();	
			$first = $session->get('first');
			$second = $session->get('second');
			$score = $session->get('score');
			$chance = $session->get('chance2');
			$Game = new Game();
			$form = $this->createFormBuilder($Game)
			->add('guess','text')
			->add('guess2','text')
			->getForm();
			
			$url1d= $this->generateUrl('Game1D');
			$url2d=$this->generateUrl('Game2D');
			$url3D=$this->generateUrl('Game3D');
		
			
		if($this->getRequest()->getMethod()=='POST'){
					$form->bindRequest($request);
					$guess = $Game->getGuess();		
					$guess2 = $Game->getGuess2();
					$session = $this->getRequest()->getSession();
					if($session->get('chance2')==0){
					
						$session->set('chance2', 10);
						return $this->render('AcmeSepaBlogBundle:Default:lost.html.twig',array('url'=>$this->generateUrl('type')));
					
					}
					// both equal X:X, y:y
					if($first == $guess && $second == $guess2){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setHint('You won!');
						$session->set('score', $score-7);			
						$Game->setScore($session->get('score'));			
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
					}elseif ($first < $guess && $second == $guess2){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setHint('X: Lower!, Y Found');
						$session->set('score', $score-7);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
					}elseif($first > $guess && $second == $guess2){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setHint('Higher!, Y Found');
						$session->set('score', $score-7);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
					}elseif($first == $guess && $second > $guess2){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setHint('X Found, Y Higher!');
						$session->set('score', $score-7);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
					}elseif($first == $guess && $second < $guess2){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setHint('X Found, Y Lower!');
						$session->set('score', $score-7);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
					}elseif($first > $guess && $second > $guess2){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setHint('X Higher!, Y Higher!');
						$session->set('score', $score-7);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
					}elseif($first < $guess && $second < $guess2){
						$Game->setGuess('');
						$Game->setGuess2('');	
						$Game->setHint('X Lower!, Y Lower!');
						$session->set('score', $score-7);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
					}elseif($first < $guess && $second > $guess2){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setHint('X Lower!, Y Higher!');
						$session->set('score', $score-7);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
					}elseif($first > $guess && $second < $guess2){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setHint('X Higher!, Y Lower!');
						$session->set('score', $score-7);
						$Game->setScore($session->get('score'));
						$session->set('chance', $chance-1);
						$Game->setChance($session->get('chance'));
					}
					$form = $this->createFormBuilder($Game)
					->add('guess','text')
					->add('guess2','text')
					->add('chance','text',array('read_only' => true))
					->add('hint','text', array('read_only' => true))
					->add('score','text', array('read_only' => true))
					->getForm();

			
		return $this->render('AcmeSepaBlogBundle:Default:game2.html.twig', array('D1'=>$url1d,'D2'=>$url2d,'D3'=>$url3D,'visitors'=>$this->aTopRecordsAction("second"),'form' => $form->createView()));
	     }
	return $this->render('AcmeSepaBlogBundle:Default:game2.html.twig', array('D1'=>$url1d,'D2'=>$url2d,'D3'=>$url3D,'visitors'=>$this->aTopRecordsAction("second"),'form' => $form->createView()));

}






	public function D3Action(Request $request){
		$session = $this->getRequest()->getSession();	
		$first = $session->get('first');
		$second = $session->get('second');
		$third = $session->get('third');
		$score = $session->get('score');
		$chance = $session->get('chance3');
		$Game = new Game();
		$form = $this->createFormBuilder($Game)
		->add('guess','text')
		->add('guess2','text')
		->add('guess3','text')
		->getForm();

		$url1d= $this->generateUrl('Game1D');
		$url2d=$this->generateUrl('Game2D');
		$url3D=$this->generateUrl('Game3D');
		

		if($this->getRequest()->getMethod()=='POST'){
					$form->bindRequest($request);
					$guess = $Game->getGuess();		
					$guess2 = $Game->getGuess2();
					$guess3 = $Game->getGuess3();

					$session = $this->getRequest()->getSession();
					if($session->get('chance3')==0){
					
						$session->set('chance3', 10);
						return $this->render('AcmeSepaBlogBundle:Default:lost.html.twig',array('url'=>$this->generateUrl('type')));
					
					}


		if($first > $guess){
			if ($second > $guess2){
					if($third > $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Higher, Y: Higher, Z: Higher');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
	
							}

					if($third < $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Higher, Y: Higher, Z: Lower');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
					}

					if($third == $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Higher, Y: Higher, Z: Found');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));

							}
						}
			if ($second < $guess2){
					if($third > $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Higher, Y: Lower, Z: Higher');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third < $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Higher, Y: Lower, Z: Lower');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third == $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Higher, Y: Lower, Z: Found');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

						}
			if ($second == $guess2){
					if($third > $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Higher, Y: Found, Z: Higher');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third < $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Higher, Y: Found, Z: Lower');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third == $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Higher, Y: Found, Z: Found');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

						}

	}elseif($first < $guess){
		if ($second > $guess2){
					if($third > $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Lower, Y: Higher, Z: Higher');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third < $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Lower, Y: Higher, Z: Lower');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third == $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Lower, Y: Higher, Z: Found');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}
						}

		if ($second < $guess2){
					if($third > $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Lower, Y: Lower, Z: Higher');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}
					if($third < $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Lower, Y: Lower, Z: Lower');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}
					if($third == $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Lower, Y: Lower, Z: Found');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}
						}
		if ($second == $guess2){
					if($third > $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Lower, Y: Found, Z: Higher');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third < $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Lower, Y: Found, Z: Lower');
						$session->set('score', $score-5);			

						$Game->setScore($session->get('score'));
						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}
					if($third == $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Lower, Y: Found, Z: Found');
						$session->set('score', $score-5);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
					}
				}
		}elseif($first == $guess){
			if ($second > $guess2){
					if($third > $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Found, Y:Higher, Z:Higher');
						$session->set('score', $score-1);			

						$Game->setScore($session->get('score'));
						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third < $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Found, Y:Higher, Z:Lower');
						$session->set('score', $score-1);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third == $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Found, Y:Higher, Z:Found');
						$session->set('score', $score-1);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

						}

		if ($second < $guess2){
					if($third > $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Found, Y:Lower, Z:Higher');
						$session->set('score', $score-1);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third < $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Found, Y:Lower, Z:Lower');
						$session->set('score', $score-1);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

					if($third == $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Found, Y:Lower, Z:Found');
						$session->set('score', $score-1);			

						$Game->setScore($session->get('score'));
						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}

						}
		if ($second == $guess2){
					if($third > $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Found, Y:Found, Z:Higher');
						$session->set('score', $score-1);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}
					if($third < $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Found, Y:Found, Z:Lower');
						$session->set('score', $score-1);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}
					if($third == $guess3){
						$Game->setGuess('');
						$Game->setGuess2('');
						$Game->setGuess3('');
						$Game->setHint('X: Found, Y:Found, Z:Found');
						$session->set('score', $score-1);			
						$Game->setScore($session->get('score'));

						
						$session->set('chance3', $chance-1);
						$Game->setChance($session->get('chance'));
							}
						}
				}
				

					$form = $this->createFormBuilder($Game)
					->add('guess','text')
					->add('guess2','text')
					->add('guess3','text')
					->add('chance','text',array('read_only' => true))
					->add('hint','text', array('read_only' => true))
					->add('score','text', array('read_only' => true))
					->getForm();
			
		return $this->render('AcmeSepaBlogBundle:Default:game3.html.twig', array('D1'=>$url1d,'D2'=>$url2d,'D3'=>$url3D,'visitors'=>$this->aTopRecordsAction("third"),'form' => $form->createView()));
	     }
	return $this->render('AcmeSepaBlogBundle:Default:game3.html.twig', array('D1'=>$url1d,'D2'=>$url2d,'D3'=>$url3D,'visitors'=>$this->aTopRecordsAction("third"),'form' => $form->createView()));
}
}


