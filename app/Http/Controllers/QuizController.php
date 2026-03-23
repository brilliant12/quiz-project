<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
  public function quiz(Request $request)
  {
    if (!$request->session()->has('score')) {
      $request->session()->put('score', 0);
      $request->session()->put('answer', 0);
    }
    return view('quiz');
  }

  public function Getquiz(Request $request)
  {
    $isCorrect = $request->get('isCorrect');
    $quizLabel = $request->get('quizLabel');
    $quizLableArr = [
      'easy' => [rand(1, 20), rand(1, 20)],
      'medium' => [rand(50, 20),  rand(50, 20)],
      'hard' => [rand(1, 1000), rand(1, 1000),]

    ];
    [$first, $second] = $quizLableArr[$quizLabel ?? 'easy'];
    if ($isCorrect === true || $isCorrect === 'true' || $isCorrect === 1 || $isCorrect === '1') {
      $this->updateScoreCart($request);
    }

    $getOp = rand(1, 3);
    $operation = ['1' => '+', 2 => '-', 3 => '*'];
    $firstNumber = $first;
    $secondNumber =  $second;
    $getResult = $this->performOperation($operation[$getOp], $firstNumber, $secondNumber);
    $arr = [
      'question' => $getResult['que'],
      'answer' =>   $getResult['ans'],
      'option1' =>  $getResult['option1'],
      'option2' =>  $getResult['option2'],
      'option3' =>  $getResult['option3'],
      'option4' =>  $getResult['option4']

    ];

    return response()->json(['status' => 'success', 'score' => $request->session()->get('score'), 'answerd' => $request->session()->get('answer'), 'skipq' => $request->session()->get('QuizSkip'), 'data' => $arr]);
  }

  public function performOperation($op, $firstNumber, $secondNumber)
  {
    $que = " $firstNumber " . " $op " . " $secondNumber = ? ";
    $ans = '';
    if ($op == '+') {
      $ans = $firstNumber + $secondNumber;
    }
    if ($op == '-') {
      $ans = $firstNumber - $secondNumber;
    }
    if ($op == '*') {
      $ans = $firstNumber * $secondNumber;
    }
    $options = [$ans - 2, $ans, $ans + 1, $ans + 2];
    shuffle($options);
    return [
      'que' => $que,
      'ans' => $ans,
      'option1' => $options[0],
      'option2' => $options[1],
      'option3' => $options[2],
      'option4' => $options[3]
    ];
  }

  public function updateScoreCart(Request $request)
  {
    $sum = $request->session()->get('score', 0) + 10;
    $answerd = $request->session()->get('answer', 0) + 1;
    $request->session()->put('score', $sum);
    $request->session()->put('answer', $answerd);
  }

  public function resetSession(Request $request)
  {
    $request->session()->forget(['score', 'answer']);
    return response()->json(['status' => 'reset']);
  }


  public function QuizLabel(Request $request)
  {
    $label = $request->get('mode');
    $request->session()->put('Quizlabel', $label);

    return response()->json(['status' => 'success', 'data' => ['message' => 'Quiz Label changed Success']]);
  }

  public function skipQuiz(Request $request)
  {
    
    $skip = $request->session()->get('QuizSkip', 0) + 1;
    $request->session()->put('QuizSkip', $skip);
 
    return response()->json(['status' => 'success', 'data' => ['message' => 'Skipped Successfully', 'skipQ' => $skip]]);
  }
}

