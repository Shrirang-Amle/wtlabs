package com.bookstore.controller;

import com.bookstore.model.Book;
import com.bookstore.repository.BookRepository;
import jakarta.servlet.http.HttpSession;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
public class HomeController {
    private static final String CART_SESSION_KEY = "cart";

    private final BookRepository bookRepository;

    public HomeController(BookRepository bookRepository) {
        this.bookRepository = bookRepository;
    }

    @GetMapping("/")
    public String home(Model model, HttpSession session) {
        model.addAttribute("books", bookRepository.findAll());
        model.addAttribute("cartCount", getCart(session).size());
        return "home";
    }

    @GetMapping("/catalog")
    public String catalog(Model model, HttpSession session) {
        model.addAttribute("books", bookRepository.findAll());
        model.addAttribute("cartCount", getCart(session).size());
        return "catalog";
    }

    @PostMapping("/cart/add/{bookId}")
    public String addToCart(@PathVariable Long bookId, HttpSession session) {
        Optional<Book> book = bookRepository.findById(bookId);
        book.ifPresent(value -> getCart(session).add(value));
        return "redirect:/cart";
    }

    @GetMapping("/cart")
    public String cart(Model model, HttpSession session) {
        List<Book> cart = getCart(session);
        double total = cart.stream().mapToDouble(Book::getPrice).sum();

        model.addAttribute("cartItems", cart);
        model.addAttribute("cartCount", cart.size());
        model.addAttribute("total", total);
        return "cart";
    }

    @PostMapping("/cart/remove/{index}")
    public String removeFromCart(@PathVariable int index, HttpSession session) {
        List<Book> cart = getCart(session);
        if (index >= 0 && index < cart.size()) {
            cart.remove(index);
        }
        return "redirect:/cart";
    }

    @SuppressWarnings("unchecked")
    private List<Book> getCart(HttpSession session) {
        Object cart = session.getAttribute(CART_SESSION_KEY);
        if (cart == null) {
            List<Book> newCart = new ArrayList<>();
            session.setAttribute(CART_SESSION_KEY, newCart);
            return newCart;
        }
        return (List<Book>) cart;
    }
}
