<div class="panel panel-lined mb30 todo" ng-controller="TodoCtrl">
    <div class="panel-heading">
        <i>Todo List</i>
        <button class="btn btn-primary btn-xs right" ng-click="markAll(allChecked)">Toggle All</button>
    </div>
    <div class="panel-body">
        <ul class="list-unstyled todo-list">
            <li ng-repeat="todo in todos | filter: statusFilter track by $index"
                ng-class="{completed: todo.completed, editing: todo === editedTodo}">
                <div class="ui-checkbox ui-checkbox-primary" ui-checkbox data-ng-class="{checked: todo.completed}">
                    <label>
                        <input type="checkbox" class="toggle" 
                            ng-model="todo.completed" ng-change="toggleCompleted(todo)" ng-checked="todo.completed">
                            <span></span>
                    </label>
                </div>
                <div class="todo-title" ng-dblclick="editTodo(todo)">
                    {{todo.title}}
                    <form ng-submit="doneEditing(todo)" class="todo-edit">
                        <input ng-trim="false" ng-model="todo.title">
                    </form>
                </div>
                <span class="destroy fa fa-close right" ng-click="removeTodo(todo)"></span>

                
            </li>
        </ul>

        <!-- Add todo input -->
        <form id="input-todo" class="input-todo" data-ng-submit="addTodo()">
            <input placeholder="Write some todo task here..." type="text" 
                ng-model="newTodo">
        </form>

        <div class="panel-footer todo-foot clearfix" id="todo-filters">
            <div class="left">
                <button class="selected btn btn-xs btn-default"
                    ng-class="{active: statusFilter == ''}"
                    ng-click="filter('all')"  ng-model="todoshow" btn-radio=" 'all' ">All</button>
                <button class="btn btn-xs btn-default"
                    ng-class="{active: statusFilter.completed == false}"
                    ng-click="filter('active')"  ng-model="todoshow" btn-radio=" 'active' ">Active</button>
            </div>
            <div class="right">
                <span class="remaining btn btn-xs btn-default">{{remainingCount}} left</span>
                <button class="btn btn-primary btn-xs" ng-click="clearCompleted()">Clear Completed ({{todos.length - remainingCount}})</button>
            </div>
        </div>
    </div>
</div>