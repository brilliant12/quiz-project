<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Laravel App</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

</head>

<body>
    @php

    @endphp
    <div class="scoreboard position-absolute" style="top: 20px; right: 20px; z-index: 999;">
        <div class="card text-white bg-dark p-3 shadow">
            <h5 class="mb-1">Scoreboard</h5>
            <p class="mb-1">Score: <span id="score">0</span></p>
            <p class="mb-0">Answered: <span id="answered">0</span></p>
            <p class="mb-0">Skipped : <span id="skip">0</span></p>
        </div>
    </div>
    <h1 class="text-center"></h1>

    <div class="quiz container mt-5">
        <div class="col-md-3 justify-content-center">
            <div class="form-group">
                <label for="">Select Quiz Label</label>
                <select name="quizLabel" id="quizLabel" class="form-control" onchange="quizLabelChange(this.value)">
                    <option value="">Select</option>
                    <option value="easy" {{ request()->session()->get('Quizlabel') == 'easy' ? 'selected' : '' }}>Easy
                    </option>
                    <option value="medium" {{ request()->session()->get('Quizlabel') == 'medium' ? 'selected' : '' }}>
                        Medium
                    </option>
                    <option value="hard" {{ request()->session()->get('Quizlabel') == 'hard' ? 'selected' : '' }}>Hard
                    </option>
                </select>
            </div>
        </div>
        <div class="que">
            <div class="que_lable h4 text-center font-weight-bold">

            </div>
        </div>
        <div class="ans_opt mt-4 justify-content-center">
            <div class="option   text-center">
                <div class="col-md-1 option1 btn btn-outline-primary validate m-1"></div>
                <div class="col-md-1 option2 btn btn-outline-primary validate m-1"></div>
                <div class="col-md-1 option3 btn btn-outline-primary validate m-1"></div>
                <div class="col-md-1 option4 btn btn-outline-primary validate m-1"></div>
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-sm btn-success" onclick="skipq()">Skip Question</button>
        </div>
        <input type="hidden" value="" id="hiddenValue">
    </div>





    <script>
        $(document).ready(function() {
            nextQuestion(false);
        });

        $('.validate').click(function(e) {
            e.preventDefault();
            const ele = e.target;
            const hiddenvalue = $('#hiddenValue').val();

            if (+ele.textContent == +hiddenvalue) {

                toastr.success("correct");

                nextQuestion(true);
            } else {
                toastr.error("wrong answer");
            }
        });

        function nextQuestion(isCorrect = false) {
           
            $.ajax({
                type: "GET",
                url: "{{ url('get-quiz') }}",
                data: {
                    isCorrect: isCorrect,
                    quizLabel: $('#quizLabel').val() ?? 'easy'
                },
                success: function(response) {
                    if (response.status === "success") {
                        const data = response.data;
                        $(".que_lable").text(' What is the output of  ' + data.question);
                        document.querySelectorAll('.validate').forEach((ele, index) => {
                            ele.textContent = data[`option${(index+1)}`];
                        })

                        $('#hiddenValue').val(data.answer);
                        $('#score').text(response.score);
                        $('#answered').text(response.answerd);
                        $('#skip').text(response.skipq);
                    } else {
                        toastr.error("Failed to load quiz question.");
                    }
                },
                error: function() {
                    toastr.error("An error occurred while fetching the quiz.");
                }
            });
        }

        function quizLabelChange(mode) {
            let quizLabel = '';
            if (!mode) {
                quizLabel = 'easy';
            } else {
                quizLabel = mode;
            }
            $.ajax({
                type: "GET",
                url: "{{ url('chage-quizLabel') }}",
                data: {
                    mode: quizLabel
                },

                success: function(response) {
                    if (response.status == 'success') {
                        toastr.success(response.data.message);
                        nextQuestion();
                    }
                }
            });
        }
        function skipq() {
            $.ajax({
                type: "GET",
                url: "{{ url('skip-quiz') }}",


                success: function(response) {
                    if (response.status == 'success') {
                        toastr.success(response.data.message);
                        $('#skip').text(response.data.skipQ);


                    }
                }
            });
        }
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
