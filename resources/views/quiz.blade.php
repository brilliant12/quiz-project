<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App</title>

    <!-- Bootstrap + Toastr -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }

        .scoreboard .card {
            background: linear-gradient(135deg, #91a8b4, #dc3545);
            border-radius: 12px;

            font-family: math;

        }

        .quiz {
            /* background: rgba(255, 255, 255, 0.1); */
            border-radius: 16px;
            padding: 30px;
            margin-top: 80px;
            /* box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3); */
        }

        .que_lable {
            background: linear-gradient(135deg, #773326, #1d191b);
            padding: 10px;
            border-radius: 10px;
            color: #fff;
            margin-bottom: 20px;
            font-family: math;
        }

        .option .btn {
            min-width: 95px;
            font-weight: bold;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .option .btn:hover {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: #fff;
            transform: scale(1.05);
        }

        .text-center {
            display: flex;

            justify-content: space-between;
            flex-direction: column-reverse;

        }
    </style>
</head>

<body>

    <!-- Scoreboard -->
    <div class="scoreboard position-absolute" style="top: 20px; right: 20px; z-index: 999;">
        <div class="card text-white p-3 shadow">
            <h5 class="mb-1">Scoreboard</h5>
            <p class="mb-1">Score: <span id="score">0</span></p>
            <p class="mb-0">Answered: <span id="answered">0</span></p>
            <p class="mb-0">Skipped: <span id="skip">0</span></p>
        </div>
    </div>

    <!-- Quiz Container -->
    <div class="container quiz">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <!-- Quiz Label Selector -->
                <div class="form-group text-center">
                    {{-- <label for="quizLabel" class=" ">Select Quiz Level</label> --}}
                    <select name="quizLabel" id="quizLabel" class="form-control w-100 mx-auto"
                        onchange="quizLabelChange(this.value)">
                        <option value="">Select</option>
                        <option value="easy" {{ request()->session()->get('Quizlabel') == 'easy' ? 'selected' : '' }}>
                            Easy</option>
                        <option value="medium"
                            {{ request()->session()->get('Quizlabel') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ request()->session()->get('Quizlabel') == 'hard' ? 'selected' : '' }}>
                            Hard</option>
                    </select>
                </div>

                <!-- Question -->
                <div class="que">
                    <div class="que_lable h5 text-center font-weight-bold"></div>
                </div>

                <!-- Options -->
                <div class="option text-center mt-4">
                    <button class="btn btn-outline-light m-1 option1 validate"></button>
                    <button class="btn btn-outline-light m-1 option2 validate"></button>
                    <button class="btn btn-outline-light m-1 option3 validate"></button>
                    <button class="btn btn-outline-light m-1 option4 validate"></button>
                </div>

                <!-- Skip Button -->
                <div class="text-center mt-3">
                    <button class="btn btn-outline-danger rounded  btn-lg" onclick="skipq()">Skip Question</button>
                </div>

                <input type="hidden" value="" id="hiddenValue">
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            nextQuestion(false);
        });

        $('.validate').click(function(e) {
            e.preventDefault();
            const ele = e.target;
            const hiddenvalue = $('#hiddenValue').val();

            if (+ele.textContent == +hiddenvalue) {
                toastr.success("Correct!");
                nextQuestion(true);
            } else {
                toastr.error("Wrong answer");
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
                        $(".que_lable").text('What is the output of ' + data.question);
                        document.querySelectorAll('.validate').forEach((ele, index) => {
                            ele.textContent = data[`option${(index+1)}`];
                        });
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
            let quizLabel = mode || 'easy';
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
                        nextQuestion();
                    }
                }
            });
        }
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
