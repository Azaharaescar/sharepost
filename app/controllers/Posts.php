<?php
class Posts extends Controller
{
  public function __construct()
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    $this->postModel = $this->model('Post');
    $this->userModel = $this->model('User');
  }

  public function index()
  {
    // Get posts
    $posts = $this->postModel->getPosts();

    $data = [
      'posts' => $posts
    ];

    $this->view('posts/index', $data);
  }

  public function add()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST array
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => ''
      ];

      // Validate data
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter title';
      }
      if (empty($data['body'])) {
        $data['body_err'] = 'Please enter body text';
      }

      // Make sure no errors
      if (empty($data['title_err']) && empty($data['body_err'])) {
        // Validated
        if ($this->postModel->addPost($data)) {
          flash('post_message', 'Post Added');
          redirect('posts');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('posts/add', $data);
      }
    } else {
      $data = [
        'title' => '',
        'body' => ''
      ];

      $this->view('posts/add', $data);
    }
  }

  public function edit($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // *** AQUI EMPIEZO A ARREGLAR EL BUG ***
      // Primero obtengo el post que se quiere editar
      $post = $this->postModel->getPostById($id);

      // Compruebo que el post existe (por si acaso alguien pone un ID que no existe)
      if (!$post) {
        flash('post_message', 'Post not found', 'alert alert-danger');
        redirect('posts');
      }

      // ESTO ES LO IMPORTANTE: Verifico que el usuario que intenta editar sea el dueño del post
      // Comparo el user_id del post con el user_id de la sesion actual
      if ($post->user_id != $_SESSION['user_id']) {
        flash('post_message', 'Unauthorized: You can only edit your own posts', 'alert alert-danger');
        redirect('posts');
      }
      // *** FIN DE MI ARREGLO DE SEGURIDAD ***

      // Sanitize POST array
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'id' => $id,
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => ''
      ];

      // Validate data
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter title';
      }
      if (empty($data['body'])) {
        $data['body_err'] = 'Please enter body text';
      }

      // Make sure no errors
      if (empty($data['title_err']) && empty($data['body_err'])) {
        // Validated
        if ($this->postModel->updatePost($data)) {
          flash('post_message', 'Post Updated');
          redirect('posts');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('posts/edit', $data);
      }
    } else {
      // Get existing post from model
      $post = $this->postModel->getPostById($id);

      // *** TAMBIEN ARREGLO EL BUG AQUI (cuando solo se muestra el formulario) ***
      // Verifico que existe el post
      if (!$post) {
        flash('post_message', 'Post not found', 'alert alert-danger');
        redirect('posts');
      }

      // Y tambien verifico que el usuario sea el dueño antes de mostrarle el formulario de edicion
      // Si no es el dueño, lo mando de vuelta a la lista de posts
      if ($post->user_id != $_SESSION['user_id']) {
        flash('post_message', 'Unauthorized: You can only edit your own posts', 'alert alert-danger');
        redirect('posts');
      }
      // *** FIN DEL ARREGLO ***

      $data = [
        'id' => $id,
        'title' => $post->title,
        'body' => $post->body
      ];

      $this->view('posts/edit', $data);
    }
  }

  public function show($id)
  {
    $post = $this->postModel->getPostById($id);
    $user = $this->userModel->getUserById($post->user_id);

    $data = [
      'post' => $post,
      'user' => $user
    ];

    $this->view('posts/show', $data);
  }

  public function delete($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get existing post from model
      $post = $this->postModel->getPostById($id);

      // *** AQUI TAMBIEN ARREGLO EL BUG DE BORRAR ***
      // Primero verifico que el post exista
      if (!$post) {
        flash('post_message', 'Post not found', 'alert alert-danger');
        redirect('posts');
      }

      // ESTE ES EL FIX MAS IMPORTANTE: antes no comprobaba bien si el usuario era el dueño
      // Ahora comparo el user_id del post con el de la sesion
      // Si no coinciden, NO dejo borrar y muestro un mensaje de error
      if ($post->user_id != $_SESSION['user_id']) {
        flash('post_message', 'Unauthorized: You can only delete your own posts', 'alert alert-danger');
        redirect('posts');
      }
      // *** FIN DEL ARREGLO DEL BUG ***

      if ($this->postModel->deletePost($id)) {
        flash('post_message', 'Post Removed');
        redirect('posts');
      } else {
        die('Something went wrong');
      }
    } else {
      redirect('posts');
    }
  }
}
