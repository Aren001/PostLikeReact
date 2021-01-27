import React from 'react';

import { Switch, Route, Link, Redirect } from "react-router-dom";
import axios from 'axios';



// const UserLists = React.lazy(() => import('../userList'));
// const MessageList = React.lazy(() => import('../message'));

class Admin extends React.Component {
  constructor(props) {
    super(props);
    const token = localStorage.getItem('token');
    let loggedIn = true;
    if (token == null) {
      loggedIn = false;
    }
    this.state = {
      loggedIn,
      item: '',
      post: [],
      user: [],
      auth: {},
      page: 1,
      totalPages: 0,
      like:true
    }

    this.getPost = this.getPost.bind(this);
  }


  componentDidMount() {
    this.getPost();
  }


  //Get Post
  getPost() {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
    axios.defaults.headers.common['Accept'] = 'application/json';
    axios.defaults.headers.common['Content-Type'] = 'application/json';

    axios.get(`http://127.0.0.1:8000/api/posts?page=${this.state.page}`).then(resp => {
      console.log(resp)
      this.setState({
        post: resp.data.postData.data,
        user: resp.data.user,
        auth: resp.data.auth,
        totalPages:resp.data.postData.total
      })
    })
  }


  //Add Post
  handleChange = (e) => {
    e.preventDefault();
    const post = {
      'user_id': this.state.auth.id,
      'body': this.state.item
    }
    axios.post('http://127.0.0.1:8000/api/posts', post).then(resp => {
      console.log(resp, 'add post');
      this.setState({
        item: '',

      }, () => this.getPost())
    })

  }

  //Pagination

  handlePaginationClick = (direction) => {
    let nextPage = this.state.page;


    nextPage = direction === 'next' ? nextPage + 1 : nextPage - 1;

    this.setState({
      page: nextPage
    }, () => this.getPost());
  }


//Delete

  deletePost = (id) => {
    axios.delete(`http://127.0.0.1:8000/api/posts/${id}`).then(resp => {
      this.getPost();
    })

  }

  //LIKE
  likePost = (id) => {
    axios.post(`http://127.0.0.1:8000/api/posts/${id}/likes/?user_id=${this.state.auth.id}`).then(resp => {
      console.log(resp,'likes');
      this.setState({
        like:false,
      })
    })
  }

  //DeleteLike
  likeDelete = (id) => {
    axios.delete(`http://127.0.0.1:8000/api/posts/${id}/likes/?user_id=${this.state.auth.id}`).then(resp => {
      console.log(resp,'unlike');
      
    })
  }

  render() {


    if (this.state.loggedIn === false) {
      return <Redirect to='/' />
    }
    return (


      <div  >

        <header
          style={{
            width: '100%',
            height: '50px',
            backgroundColor: '#39B0DA',
            color: 'white',
            display: 'flex',
            justifyContent: 'space-around',
          }}
        >
          <div>
            <h1> <i>Post</i></h1>
          </div>
          <div>
            <Link
              to='/logout'
              style={{
                fontSize: '30px',
                textDecoration: 'none',
                border: '3px solid black',
                backgroundColor: 'black',
                borderRadius: '80px',
                color: 'white'
              }}>
              Logout</Link>
          </div>
        </header>

        <div style={{ display:'flex' }}>

        <div style={{ padding:'15px 15px' }}>
        <form onSubmit={this.handleChange} >
          <textarea
            naem='body'
            value={this.state.item}
            onChange={(e) => {
              this.setState({
                item: e.target.value
              })
            }}
          />
          <br/>
          <button type='submit'   > Add Post </button>
        </form>
        <br />
        <br />
        <hr />


        <div>
          {this.state.post.map(item => {

            return (<div key={item.id}>
              {this.state.user.map(e => {
                if (item.user_id === e.id) {
                  return <b key={e.id}> {e.name}   </b>
                }
              })}
              <p> {item.body} </p>
                 <button onClick={() => this.likePost(item.id)} > Like </button> 
                 <button onClick={() => this.likeDelete(item.id)}>UnLike</button>
              {this.state.auth.id==item.user_id ? <button onClick={() => this.deletePost(item.id)}  >Delete</button> : null}
              <hr />
              
            </div>)
          })}
        </div>

        <button
          onClick={() => this.handlePaginationClick('prev')}
          disabled={this.state.page <= 1}
        > ⟵ </button>
         <span >
        Page <b>{this.state.page}</b> of <b>{this.state.totalPages}</b>
      </span>
        <button
           onClick={() => this.handlePaginationClick('next')}
           disabled={this.state.page >= this.state.totalPages}
        >⟶</button>
        </div>
        <div style={{ margin:'150px 300px' }}>
          <img  src='https://miro.medium.com/max/512/1*euSnpNmxwOvtzMQB0g6oeQ.png'   width='500' height='500'  />
        </div>

      </div>
      </div>

    )
  }
}
export default Admin;