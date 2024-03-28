<?php
class ArraySweeper
{
    private $board;
    private $player1;
    private $player2;
    private $currentPlayer;
    private $mines;
    private $attempts;

    public function __construct()
    {
        $this->board = array_fill(0, 10, 0); 
        $this->player1 = "";
        $this->player2 = "";
        $this->currentPlayer = "";
        $this->mines = [];
        $this->attempts = 3;
    }

    public function startGame()
    {
        $this->getPlayerNames();
        $this->placeMines();
        $this->determineStartingPlayer();
        $this->playGame();
    }

    private function getPlayerNames()
    {
        if (isset($_POST['player1']) && isset($_POST['player2'])) {
            $this->player1 = $_POST['player1'];
            $this->player2 = $_POST['player2'];
        }
    }

    private function placeMines()
    {
        if (isset($_POST['mines'])) {
            $minesString = $_POST['mines'];
            $this->mines = explode(',', $minesString); 
        } else {
            $this->mines = [];
        }
    }

    private function determineStartingPlayer()
    {
        $this->currentPlayer = rand(0, 1) == 0 ? $this->player1 : $this->player2;
    }

    private function playGame()
    {
        echo '<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 20px;
            padding: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        input[type="text"],
        input[type="submit"] {
            padding: 8px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        label {
            font-weight: bold;
        }
        </style>';

       
        echo '<form method="post">';
        echo '<h2>Welcome to ArraySweeper: A Minesweeper Adventure with Arrays!</h2>';
        echo '<label for="player1">Enter Player 1 name:</label>';
        echo '<input type="text" id="player1" name="player1"><br>';
        echo '<label for="player2">Enter Player 2 name:</label>';
        echo '<input type="text" id="player2" name="player2"><br>';
        echo '<label for="mines">Player 1 mines (comma-separated indices):</label>';
        echo '<input type="text" id="mines" name="mines"><br>';
        echo '<input type="submit" value="Start Game">';
        echo '</form>';

      
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo '<h3>Starting player: ' . $this->currentPlayer . '</h3>';
            echo '<form method="post">';
            echo '<input type="hidden" name="player1" value="' . htmlspecialchars($_POST['player1']) . '">';
            echo '<input type="hidden" name="player2" value="' . htmlspecialchars($_POST['player2']) . '">';
            echo '<input type="hidden" name="mines" value="' . htmlspecialchars($_POST['mines']) . '">';
            echo '<h4>' . $this->currentPlayer . '\'s turn (0-9):</h4>';
            echo '<input type="text" name="index" style="width: 50px;"><br>';
            echo '<input type="submit" value="Submit">';
            echo '</form>';

            $index = isset($_POST['index']) ? intval($_POST['index']) : null;
            if ($index !== null && $this->isValidMove($index)) {
                echo '<p style="color: green;">Congratulations, you\'re still alive!</p>';
            } else {
                echo '<p style="color: red;">Boom! You\'re dead.</p>';
            }
        }
    }

    private function isValidMove($index)
    {
        return !in_array($index, $this->mines);
    }
}

$game = new ArraySweeper();
$game->startGame();
?>