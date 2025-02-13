<?php
$shortestWord = "";
$shortestLength = null;
$islandCount = null;
$stringifiedMatrix = [];
$resultMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

    // Shortest Word Finder
    if (!empty($_POST["phrase"])) 
    {
        $input = trim($_POST["phrase"]);

        function findShortestWord($str) {
            $words = explode(" ", $str);
            $shortestWord = $words[0];

            foreach ($words as $word) {
                if (strlen($word) < strlen($shortestWord)) {
                    $shortestWord = $word;
                }
            }

            return $shortestWord;
        }

        $shortestWord = findShortestWord($input);
        $shortestLength = strlen($shortestWord);
    }

    // Count Islands Functionality
    if (!empty($_POST["matrix"])) 
    {
        $matrixInput = trim($_POST["matrix"]);
        $matrix = json_decode($matrixInput, true);

        if (is_array($matrix)) {
            $rows = count($matrix);
            $cols = count($matrix[0]);
            $visited = array_fill(0, $rows, array_fill(0, $cols, false));
            $islandCount = 0;

            // Convert matrix to string format
            for ($i = 0; $i < $rows; $i++) {
                $rowString = "";
                for ($j = 0; $j < $cols; $j++) {
                    $rowString .= ($matrix[$i][$j] == 1) ? "X" : "~";
                }
                $stringifiedMatrix[] = $rowString;
            }

            // Helper function for DFS
            function dfs(&$matrix, &$visited, $x, $y, $rows, $cols) {
                $directions = [[-1, 0], [1, 0], [0, -1], [0, 1], [-1, -1], [-1, 1], [1, -1], [1, 1]];
                $stack = [[$x, $y]];
                while (!empty($stack)) {
                    [$curX, $curY] = array_pop($stack);
                    foreach ($directions as [$dx, $dy]) {
                        $newX = $curX + $dx;
                        $newY = $curY + $dy;
                        if ($newX >= 0 && $newX < $rows && $newY >= 0 && $newY < $cols && !$visited[$newX][$newY] && $matrix[$newX][$newY] == 1) {
                            $visited[$newX][$newY] = true;
                            $stack[] = [$newX, $newY];
                        }
                    }
                }
            }

            // Count islands
            for ($i = 0; $i < $rows; $i++) {
                for ($j = 0; $j < $cols; $j++) {
                    if ($matrix[$i][$j] == 1 && !$visited[$i][$j]) {
                        $visited[$i][$j] = true;
                        dfs($matrix, $visited, $i, $j, $rows, $cols);
                        $islandCount++;
                    }
                }
            }
        }
    }
    
    // Word Search Logic
    if (!empty($_POST["words"]) && !empty($_POST["target"])) 
    {

        $wordsInput = trim($_POST["words"]);
        $targetWord = trim($_POST["target"], "\"' "); 

        $wordsArray = json_decode($wordsInput, true);

        if (json_last_error() !== JSON_ERROR_NONE)
        {
            $resultMessage = "Error: Invalid JSON format. Please enter a valid list of words.";

        } 

        elseif (!is_array($wordsArray))
        {
            $resultMessage = "Error: The input should be a valid array.";

        } 
        
        else 
        {
            $matchingIndexes = [];
    
            foreach ($wordsArray as $index => $word) {
                if (trim($word, "\"' ") === $targetWord) { 
                    $matchingIndexes[] = $index;
                }
            }
    
           
            if (!empty($matchingIndexes)) {

                $indexString = implode(" and INDEX ", $matchingIndexes);

                $resultMessage = "OUTPUT = INDEX " . $indexString;
            } else 
            {

                $resultMessage = "No match found for '$targetWord'.";
            }
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortest Word & Island Counter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

    <div class="container text-center">
        <div class="card p-4 shadow-lg w-75 mx-auto">
            <h2 class="mb-3">Shortest Word Finder, Island Counter & Word Search</h2>
            
            <form method="post">
                <!-- Shortest Word Finder -->
                <div class="mb-3">
                    <label class="form-label">Enter a phrase:</label>
                    <input type="text" name="phrase" class="form-control text-center">
                </div>
                
                <!-- Count Islands -->
                <div class="mb-3">
                    <label class="form-label">Enter a 2D matrix (JSON format, e.g. [[1,1,1,1],[0,1,1,0],[0,1,0,1],[1,1,0,0]]):</label>
                    <input type="text" name="matrix" class="form-control text-center">
                </div>

                <!-- Word Search -->
                <div class="mb-3">
                    <label class="form-label">Enter words e.g. ["I","TWO","FORTY","THREE","JEN","TWO","tWo","Two"]</label>
                    <input type="text" name="words" class="form-control text-center">
                </div>
                <div class="mb-3">
                    <label class="form-label">Enter target word:</label>
                    <input type="text" name="target" class="form-control text-center">
                </div>
                
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            <!-- Shortest Word Result -->
            <?php if (isset($shortestLength)): ?>
                <div class="mt-4">
                    <p class="fs-4"><strong><?php echo $shortestLength; ?></strong> - BECAUSE THE SHORTEST WORD IS: "<strong><?php echo $shortestWord ?></strong>"</p>
                </div>
            <?php endif; ?>

            <!-- Island Count Result -->
            <?php if (!empty($stringifiedMatrix)): ?>
                <div class="mt-4">
                    <h4>Island Representation:</h4>
                    <pre class="fs-5"><?php echo implode("\n", $stringifiedMatrix); ?></pre>
                    <!-- <p class="fs-4">Number of Islands: <strong><?php echo $islandCount; ?></strong></p> -->
                </div>
            <?php endif; ?>

            <!-- Word Search Result -->
            <?php if (!empty($resultMessage)): ?>
                <div class="mt-4">
                    <?= htmlspecialchars($resultMessage) ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>
