

-> view(?User $user, Activity $activity): Cette méthode permet à tous les utilisateurs (même les utilisateurs non connectés, d'où le ?User) de visualiser une activité. La valeur de retour est simplement true.

-> create(User $user): Ici, j'ai supposé que la création d'une activité devrait être réservée aux utilisateurs authentifiés. Si vous souhaitez que tout le monde puisse créer, vous pouvez retourner true ici aussi. J'ai utilisé $user->exists() pour vérifier si l'utilisateur est connecté.

-> update(User $user, Activity $activity) et delete(User $user, Activity $activity): Pour ces actions, la politique vérifie si l'ID de l'utilisateur connecté ($user->id) correspond à l'ID de l'utilisateur responsable de l'activité ($activity->responsible_user_id) OU si le rôle de l'utilisateur est 'Administrateur'. Si l'une de ces conditions est vraie, l'action est autorisée.

-> restore(User $user, Activity $activity) et forceDelete(User $user, Activity $activity): Ces méthodes sont généralement réservées aux administrateurs pour gérer les activités supprimées. Par conséquent, elles vérifient uniquement si l'utilisateur a le rôle 'Administrateur'.