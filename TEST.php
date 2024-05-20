<?php
    // Start session at the beginning of the script
    session_start();

    // Initialize the todo list as an associative array
    $todoList = isset($_SESSION["todoList"]) ? $_SESSION["todoList"] : array();

    // Function to prepare a task
    function prepareTask($taskName) {
        // Sanitize task name to prevent XSS
        return htmlspecialchars($taskName);
    }

    // Function to delete a task
    function deleteTask($taskId, &$todoList) {
        if (isset($todoList[$taskId])) {
            unset($todoList[$taskId]);
        }
    }

    // Check if the request method is POST
    if($_SERVER["REQUEST_METHOD"] =="POST") {
        // Check if the task input is not empty
        if (!empty($_POST["task"])) {
            // Prepare the task and add it to the todo list
            $taskId = uniqid(); // Generate a unique identifier for the task
            $todoList[$taskId] = prepareTask($_POST["task"]);
            $_SESSION["todoList"] = $todoList; // Update session data
        } else {
            echo '<script>alert("Error: there is no data to add in array")</script>';
            exit;
        }
    }

    // Check if a task deletion request is received
    if (isset($_GET['delete']) && isset($_GET['task'])) {
        // Delete the task
        deleteTask($_GET['task'], $todoList);
        $_SESSION["todoList"] = $todoList; // Update session data
        // Redirect back to the same page after deleting the task
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .card-header {
            background-color: #f0f0f0;
            padding: 10px 15px;
            font-weight: bold;
        }
        .card-body {
            padding: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .list-group-item {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            background-color: #fff;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .list-group-item .delete-btn {
            color: #dc3545;
            background-color: transparent;
            border: 1px solid #dc3545;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }
        .list-group-item .delete-btn:hover {
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">To-Do List</h1>
        <div class="card">
            <div class="card-header">Add a new task</div>
            <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <input type="text" class="form-control" name="task" placeholder="Enter your task here">
                    </div>
                    <button type="submit" class="btn-primary">Add Task</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Tasks</div>
            <ul class="list-group list-group-flush">
            <?php
                // Display tasks
                foreach ($todoList as $taskId => $task) {
                    echo '<div class="d-flex p-2 bd-highlight w-100 justify-content-between">
                            <li class="list-group-item w-100">' . $task . ' </li>
                            <a href="?delete=true&task=' . $taskId . '" class="delete-btn">Delete</a>
                          </div>';
                }
            ?>
            </ul>
        </div>
    </div>
</body>
</html>
